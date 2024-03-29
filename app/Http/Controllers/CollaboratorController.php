<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\CollaboratorLink;
use App\Models\Link;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


/**
 * Controlador que trata as tarefas relacionadas ao model Collaborator
 */
class CollaboratorController extends Controller
{
    protected $theme;

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
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
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
     * Salva um novo colaborador no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'email' => 'email|nullable',
            'vinculo' => 'required',
            'funcao' => 'required',
            'lattes' => 'url|nullable',
            'github' => 'url|nullable',

        ]);

        $validatorLinks = Validator::make($request->only('linkUrl'), [
            'linkUrl.*' => 'url|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        if ($validatorLinks->fails()) {
            return redirect()->back()->withInput()->withErrors('A url do link está em um formato inválido');
        }


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

    
    public function show($id)
    {
        //
    }

    /**
     * Mostra um formulário para editar as informações do colaborador.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collaborator = Collaborator::find($id);

        return view('collaborators.edit', ['collaborator' => $collaborator])
            ->with('theme', $this->theme);
    }

    /**
     * Atualiza um colaborador no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|nullable',
            'bond' => 'required',
            'role' => 'required',
            'lattes' => 'url|nullable',
            'github' => 'url|nullable',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $validatorLinkUrl = Validator::make($request->only('linkUrl'), [
            'linkUrl.*' => 'url|nullable'
        ]);

        if ($validatorLinkUrl->fails()) {
            return redirect()->back()->withInput()->withErrors('A url do link está em um formato inválido');
        }

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
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if (Storage::disk('public')->exists($collaborator->urlPhoto)) {
                Storage::disk('public')->delete($collaborator->urlPhoto);
            }
            $nomeArquivo = $request->file('photo')->store('img_profiles', 'public');
            $collaborator->urlPhoto = $nomeArquivo;
        } else {
            if (Storage::disk('public')->exists($collaborator->urlPhoto)) {
                Storage::disk('public')->delete($collaborator->urlPhoto);
                $collaborator->urlPhoto = null;
            }
        }


        $collaborator->save();
        $linkNames = $request->linkName;
        $linkUrls = $request->linkUrl;
        $links = $collaborator->links;
        foreach ($links as $link) {
            $link->delete();
        }
        if (isset($linkNames) && isset($linkUrls)) {

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
     * Apaga o colaborador do banco de dados
     *
     * @param  int  $id Identificador do colaborador.
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
