<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index(Request $request){
        $manager = Collaborator::query()->where('isManager',true)->first();
        $actualCollabs = Collaborator::query()->where('isManager',false)->where('active',true)->get();
        $formerCollabs = Collaborator::query()->where('active',false)->get();
        $currentCollaboratorsSection = null;
        $formerCollaboratorsSection = null;
        $query = Information::query()->where('name',"sectionNameCurrentCollaborators");
        if($query->exists()){
            $currentCollaboratorsSection = $query->first();
        }
        $query = Information::query()->where('name',"sectionNameFormerCollaborators");
        if($query->exists()){
            $formerCollaboratorsSection = $query->first();
        }
        return view('information', ['manager' => $manager,
                                    'formerCollaborators' => $formerCollabs,
                                    'collaborators' => $actualCollabs,
                                    'sectionNameCurrentCollaborators' => $currentCollaboratorsSection->value,
                                    'sectionNameFormerCollaborators' =>  $formerCollaboratorsSection->value,
                                    'idcurrent' => $currentCollaboratorsSection->id,
                                    'idformer' => $formerCollaboratorsSection->id
                                ]);

    }


    public function update(Request $request){
        
        $idCurrentCollabsText = $request['id-current'];
        $idFormerCollabsText = $request['id-former'];
        Information::where('id',$idCurrentCollabsText)->update(['value'=>$request['text-current']]);
        Information::where('id',$idFormerCollabsText)->update(['value'=>$request['text-former']]);
        return redirect()->back();
        
    }

    
}