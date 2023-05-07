<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index(Request $request){
        $manager = Collaborator::query()->where('isManager',true)->first();
        $actualCollabs = Collaborator::query()->where('isManager',false)->where('active',true)->get();
        $formerCollabs = Collaborator::query()->where('active',false)->get();
        

       
        return view('information', ['manager' => $manager,
                                    'formerCollaborators' => $formerCollabs,
                                    'collaborators' => $actualCollabs]);

    }
}
