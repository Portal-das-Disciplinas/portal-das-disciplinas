<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\CollaboratorProduction;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CollaboratorProductionController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin')->except('show');
    }

    public function show(Request $request)
    {

        $collaborator = Collaborator::find($request->idCollaborator);
        $collaboratorName = $collaborator->name;
        $fullName = explode(" ",$collaboratorName);
        if(count($fullName) > 1){
            $collaboratorName = $fullName[0] . " " . $fullName[count($fullName) - 1]; 
        }

        $collaboratorProductions = CollaboratorProduction::where('collaborator_id','=',$collaborator->id)->orderBy('created_at','desc')->get();
        $opinionLinkForm = Link::where('name','opinionForm')->first();

        return view('collaborator_production.show')->with('theme',$this->theme)
            ->with('collaborator', $collaborator)
            ->with('collaboratorName', $collaboratorName)
            ->with('collaboratorProductions', $collaboratorProductions)
            ->with('opinionLinkForm',$opinionLinkForm);
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
