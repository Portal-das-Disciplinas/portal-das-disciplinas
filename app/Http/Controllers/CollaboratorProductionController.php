<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\CollaboratorProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollaboratorProductionController extends Controller
{

    public function index()
    {
    }

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

        return redirect()->route('information');
    }
}
