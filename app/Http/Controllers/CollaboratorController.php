<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\CollaboratorLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollaboratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
    }


    public function __construct()
    {
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isManager = false;
        $active = true;

        if ($request->coordenador == 'on') {
            $isManager = true;
        }
        if ($request->ativo != 'on') {
            $active = false;
        }

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {

            $nomeArquivo = $request->file('foto')->store('img_profiles', 'public');
        }

        $col = new Collaborator();
        $col->name = $request->nome;
        $col->email = $request->email;
        $col->bond = $request->vinculo;
        $col->role = $request->funcao;
        $col->lattes = $request->lattes;
        $col->github = $request->github;
        $col->isManager = $isManager;
        $col->active = $active;
        $col->joinDate = $request->joinDate;
        $col->leaveDate = $request->leaveDate;
        if (isset($nomeArquivo)) {
            $col->urlPhoto =  $nomeArquivo;
        }
        $saved = $col->save();
        if ($saved) {
            $linkNames = $request->linkName;
            $linkUrls = $request->linkUrl;
            if (isset($linkNames) && isset($linkUrls)) {
                for ($i = 0; $i < count($linkNames); $i++) {
                    if ($linkNames[$i] != "" && $linkUrls[$i] != "") {
                        CollaboratorLink::create([
                            'name' => $linkNames[$i],
                            'url' => $linkUrls[$i],
                            'collaborator_id' => $col->id
                        ]);
                    }
                }
            }
        }

        return redirect()->route('information');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collaborator = Collaborator::find($id);

        return view('collaborators.edit', ['collaborator' => $collaborator]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $active = false;
        $isManager = false;

        if ($request->active == 'on') {
            $active = true;
        }

        if ($request->isManager == 'on') {
            $isManager = true;
        }
        $collaborator = Collaborator::find($id);
        $collaborator->name = $request->name;
        $collaborator->email = $request->email;
        $collaborator->bond = $request->bond;
        $collaborator->role = $request->role;
        $collaborator->lattes = $request->lattes;
        $collaborator->github = $request->github;
        $collaborator->active = $active;
        $collaborator->isManager = $isManager;
        $collaborator->joinDate = $request->joinDate;
        $collaborator->leaveDate = $request->leaveDate;

        if($request->imageChanged == "on"){
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                if (Storage::disk('public')->exists($collaborator->urlPhoto)) {
                    Storage::disk('public')->delete($collaborator->urlPhoto);
                }
                $nomeArquivo = $request->file('photo')->store('img_profiles', 'public');
                $collaborator->urlPhoto = $nomeArquivo;
            }
            else{
                if (Storage::disk('public')->exists($collaborator->urlPhoto)) {
                    Storage::disk('public')->delete($collaborator->urlPhoto);
                    $collaborator->urlPhoto = null;
                }
            }
        }

        $collaborator->save();
        //$linkIds = $request->linkId;
        $linkNames = $request->linkName;
        $linkUrls = $request->linkUrl;
        $links = $collaborator->links;
        foreach ($links as $link) {
            $link->delete();
        }
        if (isset($linkNames)) {
            for ($i = 0; $i < count($linkNames); $i++) {
                if ($linkNames[$i] != "" && $linkUrls[$i] != "") {
                    CollaboratorLink::create([
                        'name' => $linkNames[$i],
                        'url' => $linkUrls[$i],
                        'collaborator_id' => $collaborator->id
                    ]);
                }
            }
        }


        return redirect()->route('information');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collaborator = Collaborator::find($id);
        if (Storage::disk('public')->exists($collaborator->urlPhoto)) {
            Storage::disk('public')->delete($collaborator->urlPhoto);
        }
        $collaborator->delete();
        return redirect()->back();
    }
}
