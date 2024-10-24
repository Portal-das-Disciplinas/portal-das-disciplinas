<?php

namespace App\Http\Controllers;

use App\Http\Requests\Professor\CreateRequest;
use App\Http\Requests\Professor\StoreRequest;
use App\Http\Requests\Professor\UpdateRequest;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Professor;

/**
 * Classe que realiza tarefas relacionadas com o Professor.
 */
class ProfessorUserController extends Controller
{
    const VIEW_PATH = "admin.";
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);

    }

    /**
     * Retorna a view com as listagem dos professores
     */
    public function index()
    {
        $professors = Professor::query()->with([
            'user',
        ])->get();
        //dd($professors);
        return view(self::VIEW_PATH . 'professor.' . 'index', compact('professors'))->with('theme', $this->theme);
    }

    /**
     * Exibe um formulário para criação de um professor.
     * @param $request Objeto contendo informações da requisição http.
     */
    public function create(CreateRequest $request)
    {
        return view(self::VIEW_PATH . 'professor.' . 'create')->with('theme', $this->theme);
    }

    /**
     * Exibe um formulário para edição das informações de um professor.
     * @param $id Identificador único do professor.
     */
    public function edit($id)
    {
        $professor = Professor::where('id', $id)->with('user')->first();
        $is_teacher = $professor->user->role_id == 3;

        $opinionLinkForm = Link::where('name','opinionForm')->first();
        return view(self::VIEW_PATH . 'professor.edit')
            ->with('professor', $professor)
            ->with('is_teacher', $is_teacher)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinionLinkForm)
            ->with('showOpinionForm',true);
    }

    /**
     * Armazena o professor no banco de dados.
     * @param $request Objeto contendo as informções da requisição http.
     */
    public function store(StoreRequest $request)
    {


        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role_id' => '3'
            ]);

            Professor::create([
                'name' => $user->name,
                'profile_pic_link' => null,
                'public_email' => $request->get('public_email', $user->email),
                'rede_social1' => $request->get('rede_social1', $user->rede_social1),
                'link_rsocial1' => $request->get('link_rsocial1', $user->link_rsocial1),
                'rede_social2' => $request->get('rede_social2', $user->rede_social2),
                'link_rsocial2' => $request->get('link_rsocial2', $user->link_rsocial2),
                'rede_social3' => $request->get('rede_social3', $user->rede_social3),
                'link_rsocial3' => $request->get('link_rsocial3', $user->link_rsocial3),
                'rede_social4' => $request->get('rede_social4', $user->rede_social4),
                'link_rsocial4' => $request->get('link_rsocial4', $user->link_rsocial4),
                'user_id' => $user->id
            ]);

            DB::commit();
            return redirect()->route('professores.index');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Deleta um determinado professor no banco de dados.
     * @param $id Indetificador único do professor.
     */
    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        $professor->user->delete();
        return redirect()->route('professores.index');
    }

    /**
     * Atualiza as informações do professor no banco de dados.
     * Essa função é chamada apenas se o usuário que alterou o professor é admin.
     * @param $request Objeto contendo as informações de requisição http.
     * @param $id Identificador único do Professor.
     */
    public function update(UpdateRequest $request, $id)
    {

        $professor = Professor::where('id', $id)->first();
        $user = User::where('id', $professor->user_id)->first();

        $user->name = $request->input('name');
        $professor->name = $request->input('name');

        if ($user->email != $request->input('email')) {
            if (User::where('email', $request->input('email'))->count() < 1) {
                $user->email = $request->input('email');
            } else {
                return redirect()->back()->withInput()
                    ->withErrors(['email' => 'E-mail indisponível']);
            }
        }

        $user->updated_at = now();
        $user->save();

        $professor->public_email = $request->input('public_email');
        $professor->public_link = $request->input('public_link');
        $professor->rede_social1 = $request->input('rede_social1');
        $professor->link_rsocial1 = $request->input('link_rsocial1');
        $professor->rede_social2 = $request->input('rede_social2');
        $professor->link_rsocial2 = $request->input('link_rsocial2');
        $professor->rede_social3 = $request->input('rede_social3');
        $professor->link_rsocial3 = $request->input('link_rsocial3');
        $professor->rede_social4 = $request->input('rede_social4');
        $professor->link_rsocial4 = $request->input('link_rsocial4');
        $professor->save();

        return back()
            ->with('success', 'Dados atualizado com sucesso!')
            ->with('theme', $this->theme);
    }

}
