<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Discipline;
use App\Models\AssociatedProject;

class AssocProjectController extends Controller
{
    const VIEW_PATH = 'disciplines.';

    /**
     * Aplica o middleware de autenticação.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a lista de registros visíveis ao usuário autenticado.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin) {
            $projects = AssociatedProject::with('discipline')->get();
        } else {
            $projects = AssociatedProject::whereHas('discipline', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('discipline')->get();
        }

        return view(self::VIEW_PATH . 'index', compact('projects'));
    }

    /**
     * Exibe o formulário de criação de um novo registro.
     */
    public function create()
    {
        $user = Auth::user();

        $disciplines = Discipline::orderBy('name', 'ASC')
            ->when(!$user->isAdmin, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

        return view(self::VIEW_PATH . 'create', compact('disciplines'))
            ->with('theme', 'default')
            ->with('showOpinionForm', true);
    }

    /**
     * Salva um novo registro no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'link' => 'required|url',
            'id_discipline' => 'required|exists:disciplines,id',
        ]);

        $user = Auth::user();

        if (!$user->isAdmin) {
            $discipline = Discipline::where('id', $request->id_discipline)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        AssociatedProject::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'link' => $request->link,
            'id_discipline' => $request->id_discipline,
        ]);

        return redirect()->route('associated_projects.index')->with('success', 'Projeto associado criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um registro existente.
     */
    public function edit(AssociatedProject $associatedProject)
    {
        $user = Auth::user();

        if (!$user->isAdmin && $associatedProject->discipline->user_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        $disciplines = Discipline::orderBy('name', 'ASC')
            ->when(!$user->isAdmin, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

        return view(self::VIEW_PATH . 'edit', compact('associatedProject', 'disciplines'));
    }

    /**
     * Atualiza um registro existente no banco de dados.
     */
    public function update(Request $request, AssociatedProject $associatedProject)
    {
        $user = Auth::user();

        if (!$user->isAdmin && $associatedProject->discipline->user_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'link' => 'required|url',
            'id_discipline' => 'required|exists:disciplines,id',
        ]);

        $associatedProject->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'link' => $request->link,
            'id_discipline' => $request->id_discipline,
        ]);

        return redirect()->route('associated_projects.index')->with('success', 'Projeto associado atualizado com sucesso!');
    }

    /**
     * Remove um registro do banco de dados.
     */
    public function destroy(AssociatedProject $associatedProject)
    {
        $user = Auth::user();

        if (!$user->isAdmin && $associatedProject->discipline->user_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        $associatedProject->delete();

        return redirect()->route('associated_projects.index')->with('success', 'Projeto associado excluído com sucesso!');
    }
}