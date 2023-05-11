<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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


    public function __construct(){
        
        $this->middleware('auth')->only('store');
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
        if($request->coordenador =='on'){
            if(Collaborator::query()->where('isManager',true)->exists()){
               return redirect()->back()->withErrors(['coordenador' => 'Já existe um coordenador']);
            }

        }

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $nomeArquivo = (md5($request->foto->getClientOriginalName() . strtotime("now"))) . "." . $request->foto->extension();
            $request->foto->move(public_path('/img/profiles_img/'), $nomeArquivo);
        }
        $active = false;
        $manager = true;
        if($request->ativo =='on'){
            $active=true;
        }
        if($request->coordenador !='on'){
            $manager = false;
        }
        
        $col = new Collaborator();
        $col->name = $request->nome;
        $col->email = $request->email;
        $col->bond = $request->vinculo;
        $col->role = $request->funcao;
        $col->lattes = $request->lattes;
        $col->github = $request->github;
        $col->active = $active;
        $col->isManager = $manager;
        if(isset($nomeArquivo)){
            $col->urlPhoto = "img/profiles_img/" . $nomeArquivo;
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
        //
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
        //
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
        if(File::exists(public_path($collaborator->urlFoto))){
            File::delete(public_path($collaborator->urlPhoto));
        }
        
        $collaborator->delete();
        return redirect()->back();
    }
}
