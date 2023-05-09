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
        $currentCollaboratorSection = null;
        $formerCollaboratorSection = null;
        $query = Information::query()->where('name',"sectionNameCurrentCollaborator");
        if($query->exists()){
            $currentCollaboratorSection = $query->first()->value;
        }
        $query = Information::query()->where('name',"sectionNameFormerCollaborator");
        if($query->exists()){
            $formerCollaboratorSection = $query->first()->value;
        }
        return view('information', ['manager' => $manager,
                                    'formerCollaborators' => $formerCollabs,
                                    'collaborators' => $actualCollabs,
                                    'sectionNameCurrentCollaborators' => $currentCollaboratorSection,
                                    'sectionNameFormerCollaborators' =>  $formerCollaboratorSection]);

    }

    
}