<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\CollaboratorProduction;
use App\Models\Link;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @class CollaboratorProductionController
 * @brief Controlador para gerenciar operações relacionadas à produção de colaboradores.
 */
class CollaboratorProductionController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin')->except('show');
    }

    /**
     * @brief Exibe a página de produção de um colaborador.
     * @param Request $request O objeto de requisição HTTP.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Página de visualização com detalhes da produção do colaborador.
     */
    public function show(Request $request)
    {

        $collaborator = Collaborator::find($request->idCollaborator);
        $collaboratorName = $collaborator->name;
        $fullName = explode(" ",$collaboratorName);
        if(count($fullName) > 1){
            $collaboratorName = $fullName[0] . " " . $fullName[count($fullName) - 1]; 
        }

        $collaboratorProductions = CollaboratorProduction::where('collaborator_id','=',$collaborator->id)
            ->orderBy('created_at','desc')
            ->orderBy('id','asc')
            ->paginate(20);
            
        $opinionLinkForm = Link::where('name','opinionForm')->first();

        return view('collaborator_production.show')->with('theme',$this->theme)
            ->with('collaborator', $collaborator)
            ->with('collaboratorName', $collaboratorName)
            ->with('collaboratorProductions', $collaboratorProductions)
            ->with('opinionLinkForm',$opinionLinkForm);
    }

    /**
     * @brief Armazena uma lista de produções de colaboradores em formato JSON no banco de dados.
     * @param Request $request O objeto de requisição HTTP contendo a lista de produções em formato JSON.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página anterior com feedback sobre o resultado da operação.
     */
    public function storeListJson(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'productionCollaboratorId' => 'required',
            'collaboratorProductionsJSON' => 'required | json'
        ], $message = [
            'required' => 'O atributo está nulo',
            'json' => "Formato de dados inválidos. O formato JSON é requerido"]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }
        
        $collaboratorId = $request->productionCollaboratorId;
        $productions = json_decode($request->collaboratorProductionsJSON);
        $collaborator = Collaborator::find($collaboratorId);
        $collaboratorIdDatabase = null;
        if($collaborator){
            $collaboratorIdDatabase = $collaborator->id;
        }else{
            return redirect()->back()->withErrors(['collaborator' => 'Colaborador não encontrado']);
        }
        foreach($productions as $production){
            if($production->brief == null || strlen($production->brief) < 5){
                return redirect()->back()->withErrors(['collaboratorProduction.brief' =>
                    "Descrição muito curta. Descreva a função com 5 caracteres ou mais"]);
            }
        }
        foreach($productions as $production){
            CollaboratorProduction::create([
                'collaborator_id' => $collaboratorIdDatabase,
                'brief' => $production->brief,
                'details' => $production->details
            ]);
        }

        return redirect()->back()->with('feedback_ok','Dados salvos com sucesso!');
    }


    /**
     * @brief Atualiza os dados de uma produção de colaborador no banco de dados.
     * @param Request $request O objeto de requisição HTTP contendo os dados de atualização.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página anterior com feedback sobre o resultado da operação.
     */
    public function update(Request $request){
        $inputValidator = Validator::make($request->all(),[
            'productionId' => 'required',
            'productionBrief' => 'required | min:5'
        ], [
            'required' => 'O campo Breve descrição não pode ser nulo',
            'min' => 'O campo Breve descrição tem que ter pelo menos 5 caracteres'
        ]);

        if($inputValidator->fails()){
      
            return redirect()->back()->withErrors($inputValidator);
        }

        try{
        $production = CollaboratorProduction::find($request->productionId);
        $production->brief = $request->productionBrief;
        $production->details = $request->productionDetails;
        $production->update();
        }catch(Exception $e){

            return redirect()->back()->withErrors(['update_error' => 'Erro ao atualizar no banco de dados']);
        }
        return redirect()->back()->with('feedback_ok', 'Atualizado com sucesso');
    }

    /**
     * @brief Remove uma produção de colaborador do banco de dados.
     * @param Request $request O objeto de requisição HTTP contendo o ID da produção a ser removida.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página anterior com feedback sobre o resultado da operação.
     */
    public function delete(Request $request){
        try{
        $collaboratorProduction = CollaboratorProduction::find($request->productionId);
        $collaboratorProduction->delete();
        }catch(Exception $e){
            return redirect()->back()->withErrors(["delete_error" =>"Não foi possível efetuar a remoção no banco de dados"]);
        }
        return redirect()->back()->with('feedback_ok','Deletado com sucesso');
    }
}
