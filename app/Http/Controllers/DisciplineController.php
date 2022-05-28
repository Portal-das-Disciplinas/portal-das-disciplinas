<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {

        if(is_null($request->search)){
            $disciplines = Discipline::query()
            ->with([
                'professor',
                'medias',
            ])->orderBy('name', 'ASC')->get();
        }else{
            $nome_disciplina = $request->search;
            $disciplines = Discipline::query()
            ->with([
                'professor',
                'medias',
            ])->orderBy('name', 'ASC')->where("name", "like", $nome_disciplina."%")->get();
        }
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
        $classifications = Classification::all();
        if (Auth::user()->isAdmin) {
            $professors = Professor::query()->orderBy('name', 'ASC')->get();
        }
        return view(self::VIEW_PATH . 'create', compact('professors'))
            ->with('classifications', $classifications);
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

        $classifications = Classification::all();

        if (!is_null($user)) {
            $can = $user->canDiscipline($discipline);
            return view(self::VIEW_PATH . 'show', compact('discipline', 'can'))
                ->with('classifications', $classifications);
        }

        return view(self::VIEW_PATH . 'show', compact('discipline'))
            ->with('classifications', $classifications);
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
        if (Auth::user()->isAdmin) {
            $professors = Professor::query()->orderBy('name', 'ASC')->get();
        }
        $discipline = Discipline::query()
            ->with([
                'professor',
                'medias',
                'faqs',
            ])
            ->findOrFail($id);
        $classifications = Classification::all();

        return view(self::VIEW_PATH . 'edit', compact('discipline'), compact('professors'))
            ->with('classifications', $classifications);
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
                'synopsis' => $request->input('synopsis'),
                'difficulties' => $request->input('difficulties'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id
            ]);

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

            $classificationsMap = Classification::all()->pluck('id')->toArray();
            foreach ($classificationsMap as $classificationId) {
                ClassificationDiscipline::updateOrCreate(
                    ['discipline_id' => $discipline->id, 'classification_id' => $classificationId],
                    ['value' => $request->input('classification-' . $classificationId)]
                );
            }

            DB::commit();
            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            return dd($exception);
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
