<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Enums\ClassificationID;
use App\Enums\MediaType;
use App\Exceptions\NotAuthorizedException;
use App\Exceptions\NotImplementedException;
use App\Http\Middleware\PortalAccessInfoMiddleware;
use App\Http\Requests\Discipline\CreateRequest;
use App\Http\Requests\Discipline\StoreRequest;
use App\Http\Requests\Discipline\UpdateRequest;
use App\Models\Classification;
use App\Models\DisciplinePerformanceData;
use App\Models\ClassificationDiscipline;
use App\Services\Urls\GoogleDriveService;
use App\Services\Urls\YoutubeService;
use Illuminate\Http\Request;
use \App\Models\Discipline;
use App\Models\DisciplineParticipant;
use \App\Models\Media;
use \App\Models\Emphasis;
use App\Models\Professor;
use App\Models\Link;
use App\Models\Faq;
use App\Models\ParticipantLink;
use App\Models\ProfessorMethodology;
use App\Models\SubjectConcept;
use App\Models\SubjectReference;
use App\Models\SubjectTopic;
use App\Services\APISigaa\APISigaaService;
use App\Services\DisciplinePerformanceDataService;
use App\Services\DisciplineService;
use App\Services\MethodologyService;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use stdClass;

/**
 * Controlador responsável por realizar as tarefas relacionadas com as disciplinas.
 */
class DisciplineController extends Controller
{
    const VIEW_PATH = 'disciplines.';

    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        //$this->middleware(PortalAccessInfoMiddleware::class)->only(['index','disciplineFilter','show']);
    }

    /**
     * Retorna a página com todas as disciplinas cadastradas no portal.
     * @param $request Objeto contendo as informações da requisição http.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $emphasis = Emphasis::all();
        $classifications = Classification::all()->sortBy('order');
        $studentsData = DisciplinePerformanceData::all();
        $professors_all = Professor::all()->sortBy('name');
        $emphasis = Emphasis::all();
        $disciplines = Discipline::query()->orderBy('name', 'ASC')->get();
        $opinionLinkForm = Link::where('name', 'opinionForm')->first();
        $methodologies = (new MethodologyService())->listAllMethodologies();

        return view('disciplines.index')
            ->with('disciplines', $disciplines->paginate(12))
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('showOpinionForm', true)
            ->with('opinionLinkForm', $opinionLinkForm)
            ->with('classifications', $classifications)
            ->with('studentsData', $studentsData)
            ->with('professors', $professors_all)
            ->with('methodologies', $methodologies);
    }

    public function disciplineFilter(Request $request)
    {
        $disciplineService = new DisciplineService();
        $filteredDisciplines = $disciplineService->filterDisciplines($request);
        $opinionLinkForm = Link::where('name', 'opinionForm')->first();
        $emphasis = Emphasis::all();
        $professors = Professor::all();
        $classifications = Classification::All()->sortBy('order');
        $methodologies = (new MethodologyService())->listAllMethodologies();
        return view('disciplines.index')
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinionLinkForm)
            ->with('disciplines', $filteredDisciplines->paginate(12)->withQueryString())
            ->with('emphasis', $emphasis)
            ->with('classifications', $classifications)
            ->with('professors', $professors)
            ->with('methodologies', $methodologies);
    }

    /**
     * O usuário pode visualizar opções enquanto digita por disciplinas, autocomplete.
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('query');

        // Realize uma consulta no banco de dados para encontrar disciplinas que correspondam à consulta do usuário
        $disciplines = Discipline::select('name')->distinct()->where('name', 'like', '%' . $query . '%')->limit(10)->get();

        // Retorne os resultados em um formato adequado, como JSON
        return response()->json($disciplines);
    }

    /**
     * Abre um formulário para criar um novo professor
     *
     * @param CreateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(CreateRequest $request)
    {
        $professors = new Professor();
        $classifications = Classification::all();
        $emphasis = Emphasis::all();

        if (Auth::user()->isAdmin) {
            $professors = Professor::query()->orderBy('name', 'ASC')->get();
        }
        $opinioLinkForm = Link::where('name', 'opinionForm')->first();
        return view(self::VIEW_PATH . 'create', compact('professors'))
            ->with('classifications', $classifications)
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm', true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $professor = new Professor();
            if ($user->isAdmin) {
                $professor = Professor::query()->find($request->input('professor'));
            }
            if (!isset($professor)) {
                DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['professor_error' => 'É necessário selecionar um professor para a disciplina.']);
            }

            $discipline = Discipline::create([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'description' => $request->input('synopsis'),
                'emphasis_id' => $request->input('emphasis'),
                'difficulties' => $request->input('difficulties'),
                'acquirements' => $request->input('acquirements'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id
            ]);

            if ($request->topics) {
                foreach ($request->topics as $topicName) {
                    if ($topicName != "") {
                        SubjectTopic::create([
                            'value' => $topicName,
                            'discipline_id' => $discipline->id
                        ]);
                    }
                }
            }

            if ($request->concepts) {
                foreach ($request->concepts as $conceptName) {
                    if ($conceptName != "") {
                        SubjectConcept::create([
                            'value' => $conceptName,
                            'discipline_id' => $discipline->id
                        ]);
                    }
                }
            }

            if ($request->references) {
                foreach ($request->references as $referenceName) {
                    if ($referenceName != "") {
                        SubjectReference::create([
                            'value' => $referenceName,
                            'discipline_id' => $discipline->id
                        ]);
                    }
                }
            }

            if($request->{'selected-professor-methodologies'}){
                $professorMethodologiesToSave = json_decode($request->{'selected-professor-methodologies'});
                Log::info($professorMethodologiesToSave);
                foreach($professorMethodologiesToSave as $professorMethodology){
                    $newProfessorMethodology = new ProfessorMethodology();
                    $newProfessorMethodology->{'methodology_id'} = $professorMethodology->{'methodology_id'};
                    if(Auth::user()->isProfessor && $professorMethodology->{'professor_methodology_id'} != Auth::user()->professor->id){
                        throw new NotAuthorizedException("O professor não pode criar metodologias de outros professores.");
                    }
                    $newProfessorMethodology->{'professor_id'} = $professorMethodology->{'professor_methodology_id'};
                    $newProfessorMethodology->{'professor_description'} = $professorMethodology->{'professor_description'};
                    $newProfessorMethodology->{'methodology_use_description'} = $professorMethodology->{'methodology_use_description'};
                    $value = $newProfessorMethodology->save();
                    $newProfessorMethodology->disciplines()->attach($discipline->id);
                    Log::info($value);
                    Log::info($newProfessorMethodology);
                }
            }



            if ($request->filled('media-trailer') && YoutubeService::match($request->input('media-trailer'))) {

                $url = $request->input('media-trailer');
                $mediaId = YoutubeService::getIdFromUrl($url);
                Media::create([
                    'title' => 'Trailer de ' . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => true,
                    'view_url' => 'https://www.youtube.com/embed/' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            }

            if ($request->filled('media-podcast') && GoogleDriveService::match($request->input('media-podcast'))) {
                $url = $request->input('media-podcast');
                $mediaId = GoogleDriveService::getIdFromUrl($url);
                Media::create([
                    'title' => "Podcast de " . $discipline->name,
                    'type' => MediaType::PODCAST,
                    'view_url' => "https://drive.google.com/uc?export=open&id=" . $mediaId,
                    'url' => $url,
                    'is_trailer' => false,
                    'discipline_id' => $discipline->id
                ]);
            }

            if ($request->filled('media-video') && YoutubeService::match($request->input('media-video'))) {
                $url = $request->input('media-video');
                $mediaId = YoutubeService::getIdFromUrl($url);
                Media::create([
                    'title' => "Video de " . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => false,
                    'view_url' => "https://www.youtube.com/embed/" . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id
                ]);
            }

            if ($request->filled('media-material') && GoogleDriveService::match($request->input('media-material'))) {
                $url = $request->input('media-material');
                $mediaId = GoogleDriveService::getIdFromUrl($url);
                Media::create([
                    'title' => "Materiais de " . $discipline->name,
                    'type' => MediaType::MATERIAIS,
                    'is_trailer' => false,
                    'view_url' => "https://drive.google.com/uc?export=download&id=" . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id
                ]);
            }

            // Apagar
            // $classificationsMap = [
            //     ClassificationID::METODOLOGIAS => "classificacao-metodologias",
            //     ClassificationID::DISCUSSAO => "classificacao-discussao",
            //     ClassificationID::ABORDAGEM => "classificacao-abordagem",
            //     ClassificationID::AVALIACAO => "classificacao-avaliacao",
            // ];

            $classificationsMap = Classification::all()->pluck('id')->toArray();

            foreach ($classificationsMap as $classificationId) {
                ClassificationDiscipline::create([
                    'classification_id' => $classificationId,
                    'discipline_id' => $discipline->id,
                    'value' => $request->input('classification-' . $classificationId) == null ? 0 : $request->input('classification-' . $classificationId)
                ]);
            }

            $titles = $request->input('title');
            $contents = $request->input('content');

            if ($titles != null) {
                foreach ($titles as $key => $title) {
                    if (($title == "" || $title == null) && ($contents[$key] == "" || $contents[$key] == null)) {
                        continue;
                    } else if ($title == "" || $title == null || $contents[$key] == "" || $contents[$key] == null) {
                        DB::rollBack();
                        return redirect()->back()
                            ->withErrors(['faq_error' => 'Erro ao cadastrar a FAQ.
                                 Verifique se a Faq contém o conteúdo da PERGUNTA e da RESPOSTA preenchidos.']);
                    }
                    Faq::create([
                        'discipline_id' => $discipline->id,
                        'title' => $title,
                        'content' => $contents[$key],
                    ]);
                }
            }

            $participants = json_decode($request->participantsList);
            if ($participants) {
                foreach ($participants as $participant) {
                    $participantModel = DisciplineParticipant::create([
                        'name' => $participant->name,
                        'role' => $participant->role,
                        'email' => $participant->email,
                        'discipline_id' => $discipline->id
                    ]);
                    if ($participant->links) {
                        foreach ($participant->links as $link) {
                            ParticipantLink::create([
                                'name' => $link->name,
                                'url' => $link->url,
                                'discipline_participant_id' => $participantModel->id
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            $disciplinePerformanceDataService = new DisciplinePerformanceDataService();
            $disciplinePerformanceDataService->updateDisciplinePerformanceDataValues($discipline->code);

            $allPerformanceData = DisciplinePerformanceData::all();
            $minYear = $allPerformanceData->min('year');
            $queryMinYear = $allPerformanceData->where('year', '=', $minYear);
            $minPeriod = $queryMinYear->min('period');
            $maxYear = $allPerformanceData->max('year');
            $queryMaxYear = $allPerformanceData->where('year', '=', $maxYear);
            $maxPeriod = $queryMaxYear->max('period');
            $disciplinePerformanceDataService->saveSchedules(['yearStart' => $minYear, 'periodStart' => $minPeriod, 'yearEnd' => $maxYear, 'periodEnd' => $maxPeriod]);

            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return redirect()->back()->withErrors(['error' => "Erro ao cadastrar a disciplina"])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id Identificador único da disciplina
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $service = new APISigaaService();
        $discipline = Discipline::query()
            ->with([
                'professor',
                'medias',
                'faqs',
                'classificationsDisciplines.classification',
                'subjectTopics',
                'subjectConcepts',
                'subjectReferences'
            ])
            ->findOrFail($id);
        $user = Auth::user();
        $classifications = Classification::all()->sortBy('order');
        $opinioLinkForm = Link::where('name', 'opinionForm')->first();
        if (!is_null($user)) {
            $can = $user->canDiscipline($discipline);
            return view(self::VIEW_PATH . 'show', compact('discipline', 'can'))
                ->with('classifications', $classifications)
                ->with('theme', $this->theme)
                ->with('opinionLinkForm', $opinioLinkForm)
                ->with('showOpinionForm', true)
                ->with('professorMethodologies', $discipline->professor_methodologies);
        }

        return view(self::VIEW_PATH . 'show', compact('discipline'))
            ->with('classifications', $classifications)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm', true)
            ->with('professorMethodologies', $discipline->professor_methodologies);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id Identificador único da disciplina
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $emphasis = Emphasis::all();
        $professors = new Professor();
        if (Auth::user()->isAdmin) {
            $professors = Professor::query()->orderBy('name', 'ASC')->get();
        }
        $discipline = Discipline::query()
            ->with([
                'professor',
                'medias',
                'faqs',
                'disciplineParticipants',
                'subjectTopics',
                'subjectConcepts',
                'subjectReferences'
            ])
            ->findOrFail($id);
        $classifications = Classification::query()->orderBy('order', 'ASC')->get();
        $participants = array();
        for ($i = 0; $i < count($discipline->disciplineParticipants()->get()); $i++) {
            array_push($participants, json_decode($discipline->disciplineParticipants()->get()[$i]));
            $participants[$i]->links = json_decode($discipline->disciplineParticipants()->get()[$i]->links);
        }

        $opinioLinkForm = Link::where('name', 'opinionForm')->first();
        return view(self::VIEW_PATH . 'edit', compact('discipline'), compact('professors'))
            ->with('classifications', $classifications)
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('participants', $participants)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id Identificador único da disciplina
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $professor = new Professor();

            if ($user->isAdmin) {
                $professor = Professor::query()->find($request->input('professor'));
            }

            $discipline = Discipline::query()->find($id);
            $discipline->update([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'description' => $request->input('description'),
                'emphasis_id' => $request->input('emphasis'),
                'difficulties' => $request->input('difficulties'),
                'acquirements' => $request->input('acquirements'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id,
            ]);

            $participantsFromJson = (json_decode($request->participantList));
            /* Deleta os participantes que não estão na lista da requisição */
            $databaseDisciplineParticipants = $discipline->disciplineParticipants()->get();
            foreach ($databaseDisciplineParticipants as $participant) {
                $hasParticipant = false;
                for ($i = 0; $i < count($participantsFromJson); $i++) {
                    if (isset($participantsFromJson[$i]->id) && ($participantsFromJson[$i]->id == $participant->id)) {
                        $hasParticipant = true;
                    }
                }
                if (!$hasParticipant) {
                    $discipline->disciplineParticipants()->find($participant->id)->delete();
                }
            }
            /*Inclui ou atualiza os participantes da disciplina */
            foreach ($participantsFromJson as $json) {
                $participant = new DisciplineParticipant();
                if (isset($json->id)) {
                    $participant->id = $json->id;
                }

                $participant->name = $json->name;
                $participant->role = $json->role;
                $participant->email = $json->email;
                $participant->discipline()->associate($discipline);
                if (isset($json->id)) {
                    $discipline->disciplineParticipants()->updateOrCreate(['id' => $json->id], $participant->toArray());
                } else {
                    $participant->save();
                }
                ParticipantLink::where('discipline_participant_id', $participant->id)->delete();
                foreach ($json->links as $linkJson) {
                    $link = new ParticipantLink();
                    $link->name = $linkJson->name;
                    $link->url = $linkJson->url;
                    $participant->links()->save($link);
                }
            }
            $databaseTopicsIds = SubjectTopic::where('discipline_id', '=', $discipline->id)->pluck('id');
            if (!isset($request->topicsId)) {
                SubjectTopic::where('discipline_id', '=', $discipline->id)->delete();
            } else {
                foreach ($databaseTopicsIds->all() as $key => $idTopicDatabase) {
                    if (!in_array($idTopicDatabase, $request->topicsId)) {
                        SubjectTopic::destroy($idTopicDatabase);
                    }
                }
            }

            if (isset($request->topics)) {
                foreach ($request->topics as $key => $topic) {
                    $idTopic = $request->topicsId[$key];
                    if ($idTopic == -1) {
                        SubjectTopic::create([
                            'value' => $topic,
                            'discipline_id' => $discipline->id
                        ]);
                    } else {
                        $subjectTopic = SubjectTopic::find($idTopic);
                        $subjectTopic->{'value'} = $topic;
                        $subjectTopic->save();
                    }
                }
            }

            $databaseConceptsIds = SubjectConcept::where('discipline_id', '=', $discipline->id)->pluck('id');
            if (!isset($request->conceptsId)) {
                SubjectConcept::where('discipline_id', '=', $discipline->id)->delete();
            } else {
                foreach ($databaseConceptsIds->all() as $key => $idConceptDatabase) {
                    if (!in_array($idConceptDatabase, $request->conceptsId)) {
                        SubjectConcept::destroy($idConceptDatabase);
                    }
                }
            }

            if (isset($request->concepts)) {
                foreach ($request->concepts as $key => $concept) {
                    $idConcept = $request->conceptsId[$key];
                    if ($idConcept == -1) {
                        SubjectConcept::create([
                            'value' => $concept,
                            'discipline_id' => $discipline->id
                        ]);
                    } else {
                        $subjectConcept = SubjectConcept::find($idConcept);
                        $subjectConcept->{'value'} = $concept;
                        $subjectConcept->save();
                    }
                }
            }

            $databaseReferencesIds = SubjectReference::where('discipline_id', '=', $discipline->id)->pluck('id');
            if (!isset($request->referencesId)) {
                SubjectReference::where('discipline_id', '=', $discipline->id)->delete();
            } else {
                foreach ($databaseReferencesIds->all() as $key => $idReferenceDatabase) {
                    if (!in_array($idReferenceDatabase, $request->referencesId)) {
                        SubjectReference::destroy($idReferenceDatabase);
                    }
                }
            }

            if (isset($request->references)) {
                foreach ($request->references as $key => $reference) {
                    $idReference = $request->referencesId[$key];
                    if ($idReference == -1) {
                        SubjectReference::create([
                            'value' => $reference,
                            'discipline_id' => $discipline->id
                        ]);
                    } else {
                        $subjectReference = SubjectReference::find($idReference);
                        $subjectReference->{'value'} = $reference;
                        $subjectReference->save();
                    }
                }
            }



            $url = $request->input('media-trailer') ?? '';
            $mediaId = YoutubeService::getIdFromUrl($url);

            if (!$discipline->has_trailer_media) {
                Media::create([
                    'title' => 'Trailer de ' . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => true,
                    'view_url' => 'https://www.youtube.com/embed/' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://www.youtube.com/embed/' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->trailer->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }


            $url = $request->input('media-podcast') ?? '';
            $mediaId = GoogleDriveService::getIdFromUrl($url);
            if (!$discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST)) {
                Media::create([
                    'title' => 'Podcast de ' . $discipline->name,
                    'type' => MediaType::PODCAST,
                    'is_trailer' => false,
                    'view_url' => "https://drive.google.com/uc?export=open&id=" . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://drive.google.com/uc?export=open&id=' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->getMediaByType(\App\Enums\MediaType::PODCAST)->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }

            $url = $request->input('media-video') ?? '';
            $mediaId = YoutubeService::getIdFromUrl($url);
            if (!$discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO)) {
                Media::create([
                    'title' => 'Video de ' . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => false,
                    'view_url' => 'https://www.youtube.com/embed/' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://www.youtube.com/embed/' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->getMediaByType(\App\Enums\MediaType::VIDEO)->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }

            $url = $request->input('media-material') ?? '';
            $mediaId = GoogleDriveService::getIdFromUrl($url);
            if (!$discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS)) {
                Media::create([
                    'title' => 'Material de ' . $discipline->name,
                    'type' => MediaType::MATERIAIS,
                    'is_trailer' => false,
                    'view_url' => 'https://drive.google.com/uc?export=download&id=' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://drive.google.com/uc?export=download&id=' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->getMediaByType(\App\Enums\MediaType::MATERIAIS)->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }

            // atualizar as classificações de uma disciplina pelo id
            $classification_collection = collect([]);
            $classification_collection = ClassificationDiscipline::where('discipline_id', $id)->get();
            $classificationsMap = Classification::all()->pluck('id')->toArray();
            // foreach ($classificationsMap as $classificationId) {
            //     ClassificationDiscipline::updateOrCreate(
            //         [
            //             'discipline_id' => $discipline->id,
            //             'classification_id' => $classificationId
            //         ],
            //         ['value' => $request->input('classification-' . $classificationId)]
            //     );
            // }

            foreach ($classification_collection as $col) {
                foreach ($classificationsMap as $class) {
                    ClassificationDiscipline::where('discipline_id', $id)
                        ->where('classification_id', $class)
                        ->update(['value' => $request->input('classification-' . $class)]);
                }
            }

            $faqValidator = Validator::make($request->all(), [
                'faqTitle.*' => 'required',
                'faqContent.*' => 'required'
            ]);

            if ($faqValidator->fails()) {

                return redirect()->back()->withInput()->withErrors(['faq' => 'É necessário preencher os campos de FAQ']);
            }

            $databaseFaqsIds = Faq::where('discipline_id', $discipline->id)->pluck('id')->toArray();
            $faqIds = $request->faqId;
            $faqTitles = $request->faqTitle;
            $faqContents = $request->faqContent;

            if (isset($faqIds)) {

                foreach ($faqIds as $i => $ids) {
                    if (!isset($ids) ||  $ids != 'undefined') {
                        Faq::where('id', $faqIds[$i])->update(['title' => $faqTitles[$i], 'content' => $faqContents[$i]]);
                    } else {
                        Faq::create([
                            'title' => $faqTitles[$i],
                            'content' => $faqContents[$i],
                            'discipline_id' => $discipline->id
                        ]);
                    }
                }

                if (count($databaseFaqsIds) > 0) {
                    foreach ($databaseFaqsIds as $i => $dIds) {
                        if (!in_array($dIds, $faqIds)) {
                            Faq::find($dIds)->delete();
                        }
                    }
                }
            } else {
                Faq::where('discipline_id', $discipline->id)->delete();
            }

            /* foreach ($faqsMap as $faqId) {
                Faq::updateOrCreate(
                    ['title' => $discipline->id,'title' => $faqId],
                    ['content' => $request->input('content-' . $faqId)]
                );
            }     */

            $discipline->save();
            DB::commit();
            $disciplinePerformanceDataService = new DisciplinePerformanceDataService();
            $disciplinePerformanceDataService->updateDisciplinePerformanceDataValues($discipline->code);
            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            //dd($exception);
            DB::rollBack();
            Log::error($exception);
            return redirect()->route("disciplinas.edit", $discipline->id)
                ->withInput()->withErrors(['generalError' => 'Ocorreu um erro ao salvar a disciplina']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Discipline::query()
            ->where('id', $id)
            ->delete();

        return redirect()->route('index');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $disciplines = DB::table('disciplines')
            ->select(
                'disciplines.*',
                (DB::raw("(SELECT medias.url FROM medias WHERE medias.discipline_id = disciplines.id and medias.type = 'video' and medias.is_trailer = '1' ) AS urlMedia"))
            )
            ->where('disciplines.name', 'like', "%$search%")
            ->get();

        return view('disciplines-search')
            ->with('disciplines', $disciplines)
            ->with('search', $search)
            ->with('theme', $this->theme);
    }

    public function mydisciplines()
    {


        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $id = Auth::id();
        $disciplines = Discipline::where('user_id', '=', $id)
            ->join('users', 'users.id', '=', 'disciplines.user_id')
            ->leftJoin('medias', 'disciplines.id', '=', 'medias.discipline_id')
            ->select('disciplines.*', 'users.name as nameUser')
            ->orderBy('disciplines.name', 'asc')
            ->orderBy('nameUser', 'asc')
            ->get();

        return view('my-disciplines')
            ->with('disciplines', $disciplines)
            ->with('theme', $this->theme);
    }

    /**
     * Obtém os dados de turmas consolidadas como quantidade de aprovações, reprovações e média geral.
     * @param \Illuminate\Http\Request $request Objeto contendo as informações de requição http.
     * @param string $disciplineCode Código da disciplina.
     * @param int year $ano em que a a turma foi aberta.
     * @param int período em que a turma foi aberta.
     * @return @return \Illuminate\Http\JsonResponse
     */
    function getDisciplineData(Request $request)
    {
        $apiService = new APISigaaService();

        $data = $apiService->getDisciplineData($request['codigo'], $request['idTurma'], $request['ano'], $request['periodo']);

        return response()->json($data, 200);
    }

    function getDisciplineTurmas(Request $request, $codigo)
    {
        $service = new APISigaaService();

        $data = $service->getDisciplineTurmas($codigo);

        if ($data instanceof Exception) {
            return response()->json("Não foi possível obter os dados de matrículas", 500);
        }

        return response()->json($data, 200);
    }

    function getDisciplineClassTeacher(Request $request, $codigo)
    {
        $service = new APISigaaService();

        $data = $service->getClassTeacher($codigo);

        if ($data instanceof Exception) {
            return response()->json("Não foi possível obter os dados da turma", 500);
        }

        return response()->json($data, 200);
    }

    function getCodesAndNames(Request $request)
    {
        if ($request->ajax()) {
            $disciplineCodesAndNames = [];
            $disciplines = Discipline::where("name", "like", '%' . $request->disciplineName . '%')->get();
            foreach ($disciplines as $discipline) {
                $disciplineCodeAndName = new stdClass();
                $disciplineCodeAndName->code = $discipline->code;
                $disciplineCodeAndName->name = $discipline->name;
                if (in_array($disciplineCodeAndName, $disciplineCodesAndNames) == false) {
                    array_push($disciplineCodesAndNames, $disciplineCodeAndName);
                }
            }
            return response()->json($disciplineCodesAndNames);
        }
    }

    public function addMethodologiesToDiscipline(Request $request)
    {
        $disciplineId = $request->discipline_id;
        $methodologies = [];
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            try {
                if ($request->methodologies_array) {
                    $arrayMethodologies = $request->methodologies_array;
                    foreach ($arrayMethodologies as $methodology) {
                        $addedMethodology = $methodologyService->addMethodologiesToDiscipline($request['professor_id'], $methodology['id'], $disciplineId);
                        array_push($methodologies, $addedMethodology);
                    }
                    return response()->json($methodologies, 201);
                } else {
                    return response()->json(['error' => 'Não há disciplinas para serem adicionadas'], 400);
                }
            } catch (NotAuthorizedException $e) {
                return response()->json(['error' => $e->getMessage()], 401);
            } catch (Exception $e) {
                return response()->json(['error' => 'Ocorreu um erro desconhecido'], 500);
            }
        }
    }

    public function removeMethodologyFromDiscipline(Request $request)
    {
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            try {
                $methodologyService
                    ->removeProfessorMethodologyFromDiscipline($request->discipline_id, $request->professor_methodology_id);
            } catch (NotAuthorizedException $e) {
                return response()->json(['error' => $e->getMessage()], 401);
            } catch (Exception $e){
                return response()->json(['error' => 'Erro no servidor'],500);
            }
        } else{
            throw new NotImplementedException();
        }
    }

    public function getComponentesCurriculares(Request $request, $codigo) {
        $service = new APISigaaService();

        $data = $service->getComponentesCurriculares($codigo);

        if ($data instanceof Exception) {
            return response()->json("Não foi possível obter os componentes o curriculares desta disciplina", 500);
        }

        return response()->json($data, 200);   
    }

    public function getReferenciasBibliograficas(Request $request, $codigo) {
        $service = new APISigaaService();

        $data = $service->getReferenciasBibliograficas($codigo);

        if ($data instanceof Exception) {
            return response()->json("Não foi possível obter os componentes as referencias desta disciplina", 500);
        }

        return response()->json($data, 200);   
    }
}
