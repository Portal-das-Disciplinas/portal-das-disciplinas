<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Enums\ClassificationID;
use App\Enums\MediaType;
use App\Http\Requests\Discipline\CreateRequest;
use App\Http\Requests\Discipline\StoreRequest;
use App\Http\Requests\Discipline\UpdateRequest;
use App\Models\Classification;
use App\Models\ClassificationDiscipline;
use App\Services\Urls\GoogleDriveService;
use App\Services\Urls\YoutubeService;
use Illuminate\Http\Request;
use \App\Models\Discipline;
use App\Models\DisciplineParticipant;
use \App\Models\Media;
use \App\Models\Emphasis;
use App\Models\Professor;
use App\Models\Faq;
use App\Models\Link;
use App\Models\ParticipantLink;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DisciplineController extends Controller
{
    const VIEW_PATH = 'disciplines.';

    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        
        $name_discipline = $request->name_discipline ?? null;
        // $emphasis = $request->emphasis ?? null;

        $emphasis = Emphasis::all();
        // $disciplines = Discipline::query()
        //     ->with([
        //         'professor',
        //         'medias',
        //     ])
        //     ->orderBy('name', 'ASC') 
        //     ->when(isset($name_discipline), function($query) use($name_discipline) {
        //         $query->where("name", "like", $name_discipline."%");
        //     })
        //     ->when(isset($emphasis), function($query) use($emphasis) {
        //         $query->where("name", "like", $emphasis."%");
        //     })
        //     ->get();
        $emphasis = Emphasis::all();
        $disciplines = Discipline::query()->orderBy('name','ASC')->get();
        $opinionLinkForm = Link::where('name','opinionForm')->first();
       
        return view('disciplines.index')
            // ->with('name_discipline', $name_discipline)
            ->with('disciplines', $disciplines)
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('showOpinionForm', true)
            ->with('opinionLinkForm',$opinionLinkForm);
    }

    public function disciplineFilter(Request $request)
    {
        $emphasis_all = Emphasis::all();
        $disciplines_all = Discipline::all();

        $emphasis_id = $request->emphasis;
        $discipline_name = $request->name_discipline;

        $input;
        $collection = collect([]);

        if ($discipline_name != null && $emphasis_id != null) {
            $input = Discipline::where("name", "like", "%" . $discipline_name . "%")->get();

            foreach ($input as $i) {
                if ($i->emphasis_id == $emphasis_id) {
                    $collection->push($i);
                }
            }

            return view('disciplines.index')->with('disciplines', $collection)->with('emphasis', $emphasis_all)->with('theme', $this->theme);
        } else if ($emphasis_id != null) {
            $input = Discipline::where('emphasis_id', $emphasis_id)->get();

            return view('disciplines.index')->with('disciplines', $input)->with('emphasis', $emphasis_all)->with('theme', $this->theme);
        } else if ($discipline_name != null) {
            $input = Discipline::where("name", "like", "%" . $discipline_name . "%")->get();
            return view('disciplines.index')->with('disciplines', $input)->with('emphasis', $emphasis_all)->with('theme', $this->theme);
        } else if ($emphasis_id == null) {
            $input = Discipline::where("name", "like", "%" . $discipline_name . "%")->get();

            return view('disciplines.index')->with('disciplines', $input)->with('emphasis', $emphasis_all)->with('theme', $this->theme);
        } else if ($emphasis_id == null) {
        } else {
            return redirect('/')->with('disciplines', $disciplines_all)->with('emphasis', $emphasis_all)->with('theme', $this->theme);
        }
    }

    public function disciplineAdvancedFilter(Request $request)
    {
        // dd($request);
        $emphasis_all = Emphasis::all();
        $classifications_all = ClassificationDiscipline::all();
        $disciplines_all = Discipline::all();
        $disciplines_ids = collect([]);
        $result = collect([]);
        $resultFiltered = collect([]);

        if ($request->metodologias_range == "-1" && $request->discussao_range == "-1" && $request->abordagem_range == "-1" && $request->avaliacao_range == "-1") {
            if ($request->metodologias == null) {
                // echo 'faz nada';
            } else if ($request->metodologias == "classicas") {
                $input = ClassificationDiscipline::where('classification_id', 1)->where('value', '<=', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            } else {
                $input = ClassificationDiscipline::where('classification_id', 1)->where('value', '>', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }

            if ($request->discussao == null) {
                // echo 'faz nada';
            } else if ($request->discussao == "social") {
                $input = ClassificationDiscipline::where('classification_id', 2)->where('value', '<=', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            } else {
                $input = ClassificationDiscipline::where('classification_id', 2)->where('value', '>', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }

            if ($request->abordagem == null) {
                // echo 'faz nada';
            } else if ($request->abordagem == "teorica") {
                $input = ClassificationDiscipline::where('classification_id', 3)->where('value', '<=', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            } else {
                $input = ClassificationDiscipline::where('classification_id', 3)->where('value', '>', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }

            if ($request->avaliacao == null) {
                // echo 'faz nada';
            } else if ($request->avaliacao == "provas") {
                $input = ClassificationDiscipline::where('classification_id', 4)->where('value', '<=', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            } else {
                $input = ClassificationDiscipline::where('classification_id', 4)->where('value', '>', 50)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }
        } else {
            if ($request->metodologias_range > 0) {
                $input = ClassificationDiscipline::where('classification_id', 1)->where('value', '<=', $request->metodologias_range)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }

            if ($request->discussao_range > 0) {
                $input = ClassificationDiscipline::where('classification_id', 2)->where('value', '<=', $request->discussao_range)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }

            if ($request->abordagem_range > 0) {
                $input = ClassificationDiscipline::where('classification_id', 3)->where('value', '<=', $request->abordagem_range)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }

            if ($request->avaliacao_range > 0) {
                $input = ClassificationDiscipline::where('classification_id', 4)->where('value', '<=', $request->avaliacao_range)->get();
                foreach ($input as $i) {
                    $disciplines_ids->push($i->discipline_id);
                }
            }
        }

        foreach ($disciplines_all as $d) {
            foreach ($disciplines_ids as $r) {
                if ($d->id == $r) {
                    $result->push($d);
                }
            }
        }

        $collection = $result->duplicates()->keys();

        foreach ($collection as $col) {
            $result->pull($col);
        }

        return view('disciplines.index')->with('disciplines', $result)->with('emphasis', $emphasis_all)->with('theme', $this->theme);
    }


    /**
     * Show the form for creating a new resource.
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
        $opinioLinkForm = Link::where('name','opinionForm')->first();
        return view(self::VIEW_PATH . 'create', compact('professors'))
            ->with('classifications', $classifications)
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm',true);
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

            $discipline = Discipline::create([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'description' => $request->input('synopsis'),
                'emphasis_id' => $request->input('emphasis'),
                'difficulties' => $request->input('difficulties'),
                'acquirements' => $request->input('acquirements'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id
            ]);

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
            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route("disciplinas.create")
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $discipline = Discipline::query()
            ->with([
                'professor',
                'medias',
                'faqs',
                'classificationsDisciplines.classification',
            ])
            ->findOrFail($id);
        $user = Auth::user();

        $classifications = Classification::all()->sortBy('order');
                
        $opinioLinkForm = Link::where('name','opinionForm')->first();
        if (!is_null($user)) {
            $can = $user->canDiscipline($discipline);
            return view(self::VIEW_PATH . 'show', compact('discipline', 'can'))
                ->with('classifications', $classifications)
                ->with('theme', $this->theme)
                ->with('opinionLinkForm',$opinioLinkForm)
                ->with('showOpinionForm',true);
        }
        
        return view(self::VIEW_PATH . 'show', compact('discipline'))
            ->with('classifications', $classifications)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm',true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
            ])
            ->findOrFail($id);
        $classifications = Classification::query()->orderBy('order','ASC')->get();
        $participants = array();
        for ($i = 0; $i < count($discipline->disciplineParticipants()->get()); $i++) {
            array_push($participants, json_decode($discipline->disciplineParticipants()->get()[$i]));
            $participants[$i]->links = json_decode($discipline->disciplineParticipants()->get()[$i]->links);
        }

        $opinioLinkForm = Link::where('name','opinionForm')->first();
        return view(self::VIEW_PATH . 'edit', compact('discipline'), compact('professors'))
            ->with('classifications', $classifications)
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('participants', $participants)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm',true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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
                    //$discipline->disciplineParticipants()->save($participant->toArray());
                }
                ParticipantLink::where('discipline_participant_id', $participant->id)->delete();
                foreach ($json->links as $linkJson) {
                    $link = new ParticipantLink();
                    $link->name = $linkJson->name;
                    $link->url = $linkJson->url;
                    $participant->links()->save($link);
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
        
            foreach($classification_collection as $col) {
                foreach($classificationsMap as $class){
                    ClassificationDiscipline::where('discipline_id',$id)
                    ->where('classification_id', $class)
                    ->update(['value' => $request->input('classification-'.$class)]);
                }
            }

            $faqValidator = Validator::make($request->all(), [
                'faqTitle.*' => 'required',
                'faqContent.*' => 'required'
            ]);

            if ($faqValidator->fails()) {

                return redirect()->back()->withInput()->withErrors(['faq' => 'faq']);
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
            }else{
                Faq::where('discipline_id',$discipline->id)->delete();
            }


            /* foreach ($faqsMap as $faqId) {
                Faq::updateOrCreate(
                    ['title' => $discipline->id,'title' => $faqId],
                    ['content' => $request->input('content-' . $faqId)]
                );
            }     */

            $discipline->save();
            DB::commit();
            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
            return redirect()->route("disciplinas.edit", $discipline->id)
                ->withInput();
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
}