<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classification;
use App\Models\Discipline;
use Illuminate\Support\Facades\Storage;

/**
 * @class ClassificationController
 * @brief Controlador para gerenciar operações relacionadas a classificações.
 */
class ClassificationController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    /**
     * @brief Exibe a página principal com todas as classificações.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Página de visualização com as classificações.
     */
    public function index()
    {
        $classifications = Classification::all()->sortBy('order');
        return view('admin.classification.index')
            ->with('classifications', $classifications)
            ->with('theme', $this->theme);
    }

    /**
     * @brief Exibe o formulário para criar uma nova classificação.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Página de visualização com o formulário de criação.
     */
    public function create()
    {
        return view('admin.classification.form')
            ->with('theme', $this->theme);
    }

    /**
     * @brief Armazena uma nova classificação no banco de dados.
     * @param Request $request O objeto de requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse Redireciona para a página de índice de classificações após a criação.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:classifications',
            'type_a' => 'required',
            'type_b' => 'required',
            'system_name' => 'required'
        ]);

        $lastOrder = Classification::max('order');

        $classification = new Classification();
        $classification->name = $request->name;
        $classification->type_a = $request->type_a ?? '';
        $classification->type_b = $request->type_b ?? '';
        $classification->description = $request->description ?? '';
        $classification->order = $lastOrder + 1;
        $classification->save();

        $disciplines = Discipline::all();
        $disciplines->each(function ($discipline) use ($classification) {
            $discipline->classificationsDisciplines()->create([
                'classification_id' => $classification->id,
                'discipline_id' => $discipline->id,
                'value' => 50,
            ]);
        });

        return redirect()->route('classificacoes.index');
    }
    
    /**
     * @brief Exibe a página de detalhes de uma classificação específica.
     * @param int $id O ID da classificação.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Página de visualização com detalhes da classificação.
     */
    public function show($id)
    {
        $classification = Classification::find($id);
        return view('admin.classification.form')
            ->with('classification', $classification)
            ->with('theme', $this->theme);
    }

    /**
     * @brief Exibe o formulário para editar uma classificação existente.
     * @param int $id O ID da classificação a ser editada.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Página de visualização com o formulário de edição.
     */
    public function edit($id)
    {
        $classification = Classification::find($id);
        return view('admin.classification.form')
            ->with('classification', $classification)
            ->with('id', $id)
            ->with('edit', true)
            ->with('theme', $this->theme);
    }
    
     /**
     * @brief Atualiza os dados de uma classificação existente no banco de dados.
     * @param Request $request O objeto de requisição HTTP.
     * @param int $id O ID da classificação a ser atualizada.
     * @return \Illuminate\Http\RedirectResponse Redireciona para a página de índice de classificações após a atualização.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'type_a' => 'required',
            'type_b' => 'required',
            'system_name' => 'required'
        ]);

        $classification = Classification::find($id);
        $classification->name = $request->name;
        $classification->type_a = $request->type_a ?? '';
        $classification->type_b = $request->type_b ?? '';
        $classification->description = $request->description ?? '';
        $classification->save();

        return redirect()->route('classificacoes.index');
    }

    /**
     * @brief Atualiza a ordem das classificações com base na lista fornecida.
     * @param Request $request O objeto de requisição HTTP contendo a lista de IDs.
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo a lista de IDs atualizada.
     */
    function updateClassificationOrder(Request $request){
       $data =  json_decode($request->idList);
       foreach($data as $index => $idClassification){
            $classification = Classification::find($idClassification);
            $classification->order = $index;
            $classification->save();
       }

        return response()->json($data);
    }

    /**
     * @brief Remove uma classificação do banco de dados.
     * @param int $id O ID da classificação a ser removida.
     * @return \Illuminate\Http\RedirectResponse Redireciona para a página de índice de classificações após a remoção.
     */
    public function destroy($id)
    {
        $classification = Classification::find($id);
        $classification->delete();

        $classifications = Classification::All()->sortBy('order');
        foreach($classifications as $index => $classification){
            $classification->order = $index;
            $classification->save();
        }

        return redirect()->route('classificacoes.index');
    }
}
