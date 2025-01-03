<?php

namespace App\Http\Controllers;

use App\Exceptions\MissingDataException;
use App\Exceptions\NotAuthorizedException;
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
use App\Services\InstitutionalUnitService;
use Exception;
use App\Services\ProfessorService;
use Illuminate\Support\Facades\Log;

/**
 * Classe que realiza tarefas relacionadas com o Professor.
 */
class ProfessorUserController extends Controller
{
    const VIEW_PATH = "admin.";
    protected $theme;
    protected $professorService;
    protected $institutionalUnitService;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->professorService = new ProfessorService();
        $this->institutionalUnitService = new InstitutionalUnitService();
        $this->theme = json_decode($contents, true);
    }

    /**
     * Retorna a view com as listagem dos professores
     */
    public function index()
    {
        if($this->checkIsAdmin()){
            $professors = $this->professorService->listAll();
        }elseif($this->checkIsUnitAdmin()){
            $professors = $this->professorService
                ->ListByInstitutionalUnitId(Auth::user()->unitAdmin->institutionalUnit->id);
        }
        return view('admin/professor/index',[
            'professors' => $professors,
            'theme' =>$this->theme
        ]);
    }

    /**
     * Exibe um formulário para criação de um professor.
     * @param $request Objeto contendo informações da requisição http.
     */
    public function create(CreateRequest $request)
    {
        try {
            if ($this->checkIsAdmin()) {
                $institutionalUnits = $this->institutionalUnitService->listAll();
            } elseif ($this->checkIsUnitAdmin()) {
                $institutionalUnits = collect();
                $institutionalUnit = $this->institutionalUnitService->getByUnitAdmin(Auth::user()->unitAdmin->id);
                $institutionalUnits->add($institutionalUnit);
            }
            return view(self::VIEW_PATH . 'professor.' . 'create')
                ->with('institutionalUnits', $institutionalUnits)
                ->with('theme', $this->theme);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Exibe um formulário para edição das informações de um professor.
     * @param $id Identificador único do professor.
     */
    public function edit($id)
    {
        $professor = Professor::where('id', $id)->with('user')->first();
        $is_teacher = $professor->user->role_id == 3;

        $opinionLinkForm = Link::where('name', 'opinionForm')->first();
        return view(self::VIEW_PATH . 'professor.edit')
            ->with('professor', $professor)
            ->with('is_teacher', $is_teacher)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinionLinkForm)
            ->with('showOpinionForm', true);
    }

    /**
     * Armazena o professor no banco de dados.
     * @param $request Objeto contendo as informções da requisição http.
     */
    public function store(StoreRequest $request)
    {

        try {
            if (!$this->checkIsAdmin() && !$this->checkIsUnitAdmin()) {
                throw new NotAuthorizedException("Você não tem permissão para realizar esta operação");
            }
            $this->professorService->save(
                $request->name,
                $request->email,
                $request->password,
                null,
                $request->public_email,
                $request->rede_social1,
                $request->link_rsocial1,
                $request->rede_social2,
                $request->link_rsocial2,
                $request->rede_social3,
                $request->link_rsocial3,
                $request->rede_social4,
                $request->link_rsocial4,
                $request->{'institutional-unit-id'}
            );
            return redirect()->route('professores.index');

        } catch (NotAuthorizedException $e) {
            return redirect()->back()->withInput()->withErrors(['auth_error' => $e->getMessage()]);

        } catch(MissingDataException $e){
            return redirect()->back()->withInput()->withErrors(['missing_value' => $e->getMessage()]);

        }catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['store_error' => 'Não foi possível cadastrar o professor']);
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

    private function checkIsAdmin()
    {
        return Auth::user() && Auth::user()->is_admin;
    }

    private function checkIsUnitAdmin()
    {
        return Auth::user() && Auth::user()->is_unit_admin;
    }
}
