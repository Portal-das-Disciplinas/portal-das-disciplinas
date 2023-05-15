<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
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
            if (Collaborator::query()->where('isManager', true)->exists()) {
                return redirect()->back()->withErrors(['coordenador' => 'Coordenador já existente']);
            } else {
                $isManager = true;
            }
        }
        if ($request->ativo != 'on') {
            $active = false;
        }

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            //$nomeArquivo = (md5($request->foto->getClientOriginalName() . strtotime("now"))) . "." . $request->foto->extension();
            //$request->foto->move(public_path('/img/profiles_img/'), $nomeArquivo);
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
        if (isset($nomeArquivo)) {
            // dd($nomeArquivo);
            $col->urlPhoto =  $nomeArquivo;
        }
        $col->save();
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

        if ($isManager && (Collaborator::where('isManager', true)->exists()) && ($collaborator->isManager == false)) {
            return redirect()->back()->withErrors(['coordenador' => 'Coordenador já existente']);
        }



        $collaborator->name = $request->name;
        $collaborator->email = $request->email;
        $collaborator->bond = $request->bond;
        $collaborator->role = $request->role;
        $collaborator->lattes = $request->lattes;
        $collaborator->github = $request->github;
        $collaborator->active = $active;
        $collaborator->isManager = $isManager;
        $collaborator->save();
        return redirect()->route('information');
    }

    public function updatePhoto(Request $request, $id)
    {

        $collaborator = Collaborator::find($id);
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if (Storage::disk('public')->exists($collaborator->urlPhoto)) {
                Storage::disk('public')->delete($collaborator->urlPhoto);
            }
            $nomeArquivo = $request->file('photo')->store('img_profiles', 'public');
            $collaborator->urlPhoto = $nomeArquivo;
            $collaborator->save();
            return redirect()->back()->withInput();
        }
    }

    public function deletePhoto(Request $request, $id)
    {

        $collaborator = Collaborator::find($id);
        if (Storage::disk('public')->exists($collaborator->urlPhoto)) {
            Storage::disk('public')->delete($collaborator->urlPhoto);
            $collaborator->urlPhoto = null;
            $collaborator->save();
        }

        return redirect()->back()->with('mensagem', 'Foto removida');
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
