<?php

namespace App\Http\Controllers;

use App\Enums\ClassificationID;
use App\Enums\MediaType;
use App\Http\Requests\Discipline\CreateRequest;
use App\Http\Requests\Discipline\StoreRequest;
use App\Http\Requests\Discipline\UpdateRequest;
use App\Models\ClassificationDiscipline;
use App\Services\Urls\GoogleDriveService;
use App\Services\Urls\YoutubeService;
use Illuminate\Http\Request;
use \App\Models\Discipline;
use \App\Models\Media;
use App\Models\Professor;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DisciplineController extends Controller
{
    const VIEW_PATH = 'disciplines.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $disciplines = Discipline::query()
            ->with([
                'professor',
                'medias',
            ])->orderBy('name', 'ASC')->get();

        return view(self::VIEW_PATH . 'index', compact('disciplines'));
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
        if (Auth::user()->isAdmin) {
            $professors = Professor::query()->orderBy('name', 'ASC')->get();
        }
        return view(self::VIEW_PATH . 'create', compact('professors'));
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
                'synopsis' => $request->input('synopsis'),
                'difficulties' => $request->input('difficulties'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id
            ]);

            if ($request->filled('media-trailer') && YoutubeService::match($request->input('media-trailer'))) {
                $mediaId = YoutubeService::getIdFromUrl($request->input('media-trailer'));
                Media::create([
                    'title' => 'Trailer de ' . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => true,
                    'url' => 'https://www.youtube.com/embed/' . $mediaId,
                    'discipline_id' => $discipline->id,
                ]);
            }

            if ($request->filled('media-podcast') && YoutubeService::match($request->input('media-podcast'))) {
                $mediaId = YoutubeService::getIdFromUrl($request->input('media-podcast'));
                Media::create([
                    'title' => "Podcast de " . $discipline->name,
                    'type' => MediaType::PODCAST,
                    'url' => "https://www.youtube.com/embed/" . $mediaId,
                    'is_trailer' => false,
                    'discipline_id' => $discipline->id
                ]);
            }

            if ($request->filled('media-video') && YoutubeService::match($request->input('media-video'))) {
                $mediaId = YoutubeService::getIdFromUrl($request->input('media-video'));
                Media::create([
                    'title' => "Video de " . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => false,
                    'url' => "https://www.youtube.com/embed/" . $mediaId,
                    'discipline_id' => $discipline->id
                ]);
            }

            if ($request->filled('media-material') && GoogleDriveService::match($request->input('media-material'))) {
                $mediaId = GoogleDriveService::getIdFromUrl($request->input('media-material'));
                Media::create([
                    'title' => "Materiais de " . $discipline->name,
                    'type' => MediaType::MATERIAIS,
                    'is_trailer' => false,
                    'url' => "https://drive.google.com/uc?export=download&id=" . $mediaId,
                    'discipline_id' => $discipline->id
                ]);
            }

            $classificationsMap = [
                ClassificationID::METODOLOGIAS_CLASSICAS => "classificacao-metodologias-classicas",
                ClassificationID::METODOLOGIAS_ATIVAS => "classificacao-metodologias-ativas",
                ClassificationID::DISCUSSAO_SOCIAL => "classificacao-discussao-social",
                ClassificationID::DISCUSSAO_TECNICA => "classificacao-discussao-tecnica",
                ClassificationID::ABORDAGEM_TEORICA => "classificacao-abordagem-teorica",
                ClassificationID::ABORDAGEM_PRATICA => "classificacao-abordagem-pratica",
                ClassificationID::AVALIACAO_PROVAS => "classificacao-av-provas",
                ClassificationID::AVALIACAO_ATIVIDADES => "classificacao-av-atividades"
            ];

            foreach ($classificationsMap as $classificationId => $inputValue){
                ClassificationDiscipline::create([
                    'classification_id' => $classificationId,
                    'discipline_id' => $discipline->id,
                    'value' => $request->input($inputValue) == null ? 0 : $request->input($inputValue)
                ]);
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

        if (!is_null($user)) {
            $can = $user->canDiscipline($discipline);
            return view(self::VIEW_PATH . 'show', compact('discipline', 'can'));
        }

        return view(self::VIEW_PATH . 'show', compact('discipline'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $professors = new Professor();
        if(Auth::user()->isAdmin)
        {
            $professors = Professor::query()->orderBy('name','ASC')->get();
        }
        $discipline = Discipline::query()
        ->with([
            'professor',
            'medias',
            'faqs',
        ])
        ->findOrFail($id);
        return view(self::VIEW_PATH . 'edit', compact('discipline'), compact('professors'));
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

            if($user->isAdmin)
            {
                $professor = Professor::query()->find($request->input('professor'));
            }

            $discipline = Discipline::query()->find($id);
            $discipline->update([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'synopsis' => $request->input('synopsis'),
                'difficulties' => $request->input('difficulties'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id
            ]);

            if ($request->filled('media-trailer') && YoutubeService::match($request->input('media-trailer'))) {
                $mediaId = YoutubeService::getIdFromUrl($request->input('media-trailer'));
                if(!$discipline->has_trailer_media){
                    Media::create([
                        'title' => 'Trailer de ' . $discipline->name,
                        'type' => MediaType::VIDEO,
                        'is_trailer' => true,
                        'url' => 'https://www.youtube.com/embed/' . $mediaId,
                        'discipline_id' => $discipline->id,
                    ]);
                }else{
                    Media::query()->find($discipline->trailer->id)->update([
                        'url' => "https://www.youtube.com/embed/" . $mediaId
                    ]);
                }
            }


            if ($request->filled('media-podcast') && YoutubeService::match($request->input('media-podcast'))) {
                $mediaId = YoutubeService::getIdFromUrl($request->input('media-podcast'));
                if(!$discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST)){
                    Media::create([
                        'title' => 'Podcast de ' . $discipline->name,
                        'type' => MediaType::PODCAST,
                        'is_trailer' => false,
                        'url' => 'https://www.youtube.com/embed/' . $mediaId,
                        'discipline_id' => $discipline->id,
                    ]);
                }else{
                    Media::query()->find($discipline->getMediaByType("podcast")->id)->update([
                        'url' => "https://www.youtube.com/embed/" . $mediaId
                    ]);
                }
            }

            if ($request->filled('media-video') && YoutubeService::match($request->input('media-video'))) {
                $mediaId = YoutubeService::getIdFromUrl($request->input('media-video'));
                if(!$discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO)){
                    Media::create([
                        'title' => 'Video de ' . $discipline->name,
                        'type' => MediaType::VIDEO,
                        'is_trailer' => false,
                        'url' => 'https://www.youtube.com/embed/' . $mediaId,
                        'discipline_id' => $discipline->id,
                    ]);
                }else{
                    Media::query()->find($discipline->getMediaByType("video")->id)->update([
                        'url' => "https://www.youtube.com/embed/" . $mediaId
                    ]);
                }
            }

            if ($request->filled('media-material') && GoogleDriveService::match($request->input('media-material'))) {
                $mediaId = GoogleDriveService::getIdFromUrl($request->input('media-material'));
                if(!$discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS)){
                    Media::create([
                        'title' => 'Material de ' . $discipline->name,
                        'type' => MediaType::MATERIAIS,
                        'is_trailer' => false,
                        'url' => 'https://www.youtube.com/embed/' . $mediaId,
                        'discipline_id' => $discipline->id,
                    ]);
                }else{
                    Media::query()->find($discipline->getMediaByType("material")->id)->update([
                        'url' => "https://www.youtube.com/embed/" . $mediaId
                    ]);
                }
            }

            $classificationsMap = [
                ClassificationID::METODOLOGIAS_CLASSICAS => "classificacao-metodologias-classicas",
                ClassificationID::METODOLOGIAS_ATIVAS => "classificacao-metodologias-ativas",
                ClassificationID::DISCUSSAO_SOCIAL => "classificacao-discussao-social",
                ClassificationID::DISCUSSAO_TECNICA => "classificacao-discussao-tecnica",
                ClassificationID::ABORDAGEM_TEORICA => "classificacao-abordagem-teorica",
                ClassificationID::ABORDAGEM_PRATICA => "classificacao-abordagem-pratica",
                ClassificationID::AVALIACAO_PROVAS => "classificacao-av-provas",
                ClassificationID::AVALIACAO_ATIVIDADES => "classificacao-av-atividades"
            ];

            foreach ($classificationsMap as $classificationId => $inputValue){
                ClassificationDiscipline::query()->where('discipline_id',$discipline->id)
                ->where('classification_id',$classificationId)->update([
                    'value' => $request->input($inputValue)
                ]);
            }

            DB::commit();
            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            return dd($discipline->has_trailer_media);
            // return redirect()->route("disciplinas.edit", $discipline->id)
            //     ->withInput();
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
            ->select('disciplines.*',
                (DB::raw("(SELECT medias.url FROM medias WHERE medias.discipline_id = disciplines.id and medias.type = 'video' and medias.is_trailer = '1' ) AS urlMedia")))
            ->where('disciplines.name', 'like', "%$search%")
            ->get();

        return view('disciplines-search')
            ->with('disciplines', $disciplines)
            ->with('search', $search);

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
            ->with('disciplines', $disciplines);
    }
}
