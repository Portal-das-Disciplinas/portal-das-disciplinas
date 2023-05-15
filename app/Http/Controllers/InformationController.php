<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->except('index');
    }

    public function index(Request $request)
    {
        $manager = Collaborator::query()->where('isManager', true)->first();
        $actualCollabs = Collaborator::query()->where('isManager', false)->where('active', true)->get();
        $formerCollabs = Collaborator::query()->where('active', false)->get();
        $currentCollaboratorsSection = null;
        $formerCollaboratorsSection = null;
        $query = Information::query()->where('name', "sectionNameCurrentCollaborators");
        if ($query->exists()) {
            $currentCollaboratorsSection = $query->first();
        }
        $query = Information::query()->where('name', "sectionNameFormerCollaborators");
        if ($query->exists()) {
            $formerCollaboratorsSection = $query->first();
        }

        return view('information', [
            'manager' => $manager ? $manager : null,
            'formerCollaborators' => $formerCollabs,
            'collaborators' => $actualCollabs,
            'sectionNameCurrentCollaborators' => $currentCollaboratorsSection ? $currentCollaboratorsSection->value : null,
            'sectionNameFormerCollaborators' =>  $formerCollaboratorsSection ? $formerCollaboratorsSection->value : null,
            'idcurrent' =>  $currentCollaboratorsSection ? $currentCollaboratorsSection->id : null,
            'idformer' =>   $formerCollaboratorsSection ? $formerCollaboratorsSection->id : null
        ]);
    }

    public function store(Request $request)
    {
        if ($request['id-information']) {
            Information::where('id', $request['id-information'])->update(['value' => $request['information-value']]);
            return redirect()->back();
        } else {
            Information::create([
                'name' => $request['information-name'],
                'value' => $request['information-value']
            ]);
            return redirect()->back();
        }
    }



    public function update(Request $request)
    {

        $idCurrentCollabsText = $request['id-current'];
        $idFormerCollabsText = $request['id-former'];
        Information::where('id', $idCurrentCollabsText)->update(['value' => $request['text-current']]);
        return redirect()->back();
    }

    public function StoreOrUpdate(Request $request)
    {
        if (Information::where('name', $request->name)->exists()) {
            Information::where('name', $request->name)->first()->update(['value' => $request->value]);
        } else {
            Information::create([
                'name' => $request->name,
                'value' => $request->value
            ]);
        }
        return redirect()->route('information');
    }
}
