<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\DisciplineParticipant;
use App\Models\Information;
use App\Models\Link;
use App\Services\Urls\YoutubeService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InformationController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin')->except('index');
    }

    public function index(Request $request)
    {

        $collaborators = Collaborator::All();
        $hasManagers = false;
        $hasCurrentCollaborators = false;
        $hasFormerCollaborators = false;
        foreach ($collaborators as $collaborator) {
            if ($collaborator->isManager && $collaborator->active) {
                $hasManagers = true;
            }
            if (!$collaborator->isManager && $collaborator->active) {
                $hasCurrentCollaborators = true;
            }
            if (!$collaborator->active) {
                $hasFormerCollaborators = true;
            }
        }
        $managerSection = null;
        $currentCollaboratorsSection = null;
        $formerCollaboratorsSection = null;
        $sectionCollaborateTitle = null;
        $sectionCollaborateText = null;
        $query = Information::query()->where('name', "sectionNameManagers");
        if ($query->exists()) {
            $managerSection = $query->first();
        }
        $query = Information::query()->where('name', "sectionNameCurrentCollaborators");
        if ($query->exists()) {
            $currentCollaboratorsSection = $query->first();
        }
        $query = Information::query()->where('name', "sectionNameFormerCollaborators");
        if ($query->exists()) {
            $formerCollaboratorsSection = $query->first();
        }
        $query = Information::query()->where('name', 'sectionCollaborateTitle');
        if ($query->exists()) {
            $sectionCollaborateTitle = $query->first();
        }
        $query = Information::query()->where('name', 'sectionCollaborateText');
        if ($query->exists()) {
            $sectionCollaborateText = $query->first();
        }
        $opinioLinkForm = Link::where('name','opinionForm')->first();
        $link = Information::where('name', 'linkVideoPortal')->first();
        if(isset($link) && $link->value !=""){
            $mediaId = YoutubeService::getIdFromUrl($link->value);
            $videoUrl = 'https://www.youtube.com/embed/' . $mediaId;

        }
        if($link == ""){
            $link = null;
        }
        
        $videoAboutProducers = DisciplineParticipant::query()->orderBy('name','ASC')->where('worked_on','video_about')->get();
        

        return view('information', [
            'collaborators' => $collaborators,
            'hasManagers' => $hasManagers,
            'hasCurrentCollaborators' => $hasCurrentCollaborators,
            'hasFormerCollaborators' => $hasFormerCollaborators,
            'sectionNameManagers' => $managerSection ? $managerSection->value : null,
            'sectionNameCurrentCollaborators' => $currentCollaboratorsSection ? $currentCollaboratorsSection->value : null,
            'sectionNameFormerCollaborators' =>  $formerCollaboratorsSection ? $formerCollaboratorsSection->value : null,
            'sectionCollaborateTitle' => $sectionCollaborateTitle ? $sectionCollaborateTitle->value : "",
            'sectionCollaborateText' => $sectionCollaborateText ? $sectionCollaborateText->value : "",
            'showOpinionForm' => true,
            'opinionLinkForm' => $opinioLinkForm,
            'videoAboutProducers' => $videoAboutProducers,
            'videoUrl' => $videoUrl ?? null
        ])
            ->with('theme', $this->theme);
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
        DB::beginTransaction();
        try {
            if (Information::where('name', $request->name)->exists()) {
                Information::where('name', $request->name)->first()->update(['value' => $request->value]);
            } else {
                Information::create([
                    'name' => $request->name,
                    'value' => $request->value
                ]);
            }
            DB::commit();
            return redirect()->route('information');
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
        }
    }

    public function deleteByName(Request $request, $name){
        DB::beginTransaction();
        try{
            $information = Information::where('name', $name)->first();
            $information->delete();
            DB::commit();
            return redirect()->route('information');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->withErrors(['information','Não foi possível remover']);
        }
       

    }

    
}
