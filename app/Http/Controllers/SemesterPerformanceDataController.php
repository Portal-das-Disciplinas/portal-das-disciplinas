<?php

namespace App\Http\Controllers;

use App\Models\SemesterPerformanceData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @class SemesterPerformanceDataController
 * @brief Controlador para gerenciar dados de desempenho por semestre.
 */
class SemesterPerformanceDataController extends Controller
{
    protected $theme;
    function __construct()
    {
        $this->middleware('admin');
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    /**
     * @brief Exibe a página principal de dados de desempenho por semestre.
     * @return \Illuminate\View\View Retorna a view 'discipline_performance_data/semester_performance_data'.
     */
    public function index()
    {

        $semesterPerformanceData = SemesterPerformanceData::query()->orderBy('year','desc')->orderBy('period','asc')->paginate(30);
        return view('discipline_performance_data/semester_performance_data')
            ->with('theme', $this->theme)
            ->with('semesterPerformanceData', $semesterPerformanceData);
    }

    /**
     * @brief Exclui um dado de desempenho por semestre.
     * @param int $id Identificador do dado de desempenho por semestre.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página principal.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = SemesterPerformanceData::find($id);
            $data->delete();
            DB::commit();
            return redirect()->back()->with("status","Dado removido com sucesso!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['delete'=>'Erro ao remover o dado']);
        }
    }
}
