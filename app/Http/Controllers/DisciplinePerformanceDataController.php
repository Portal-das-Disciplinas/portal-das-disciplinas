<?php

namespace App\Http\Controllers;

use App\Models\DisciplinePerformanceData;
use App\Services\DisciplinePerformanceDataService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stdClass;

/**
 * @class DisciplinePerformanceDataController
 * @brief Controlador para gerenciar operações relacionadas aos dados de desempenho de disciplinas.
 */
class DisciplinePerformanceDataController extends Controller
{
    protected $performanceDataService;
    protected $theme;
    function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->performanceDataService = new DisciplinePerformanceDataService();
        $this->middleware('admin')->except(['getDisciplinePerformanceData', 'getDisciplinePerformanceDataByInterval']);
    }

    /**
     * @brief Retorna os índices de desempenho da disciplina no formato JSON.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo os índices de desempenho.
     */
    function getDisciplinePerformanceData(Request $request)
    {
        try {
            $service = new DisciplinePerformanceDataService();
            $datas = $service->getPerformanceData($request['disciplineCode'], $request['year'], $request['period']);
            return response()->json($datas);
        } catch (Exception $e) {
            $error = new stdClass();
            $error->error = "Erro";
            return response()->json(json_encode($error));
        }
    }

    /**
     * @brief Retorna os índices de desempenho da disciplina dentro de um intervalo de tempo no formato JSON.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo os índices de desempenho dentro do intervalo especificado.
     */
    function getDisciplinePerformanceDataByInterval(Request $request)
    {
        if ($request->ajax()) {
            $service = new DisciplinePerformanceDataService();
            try {
                if (isset($request['checkAllPeriods']) && ($request['checkAllPeriods'] == 'on')) {
                    $datas = $service->getPerformanceDataByDisciplineCode($request['disciplineCode']);
                    return response()->json($datas);
                } else {
                    $datas = $service->getPerformanceDataByInterval(
                        $request['disciplineCode'],
                        $request['yearStart'],
                        $request['periodStart'],
                        $request['yearEnd'],
                        $request['periodEnd']
                    );
                    return response()->json($datas);
                }
            } catch (Exception $e) {
                $error = new stdClass();
                $error->error = "Erro";
                Log::error("error" . $e->getMessage());
                return response()->json(json_encode($error));
            }
        }
    }

    /**
     * @brief Exibe a página de índices de desempenho das disciplinas.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Página de visualização dos índices de desempenho.
     */
    function index(Request $request)
    {
        return view('discipline_performance_data.performance_data_index')->with('theme', $this->theme);
    }

     /**
     * @brief Lista os dados de desempenho da disciplina.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Página de visualização dos dados de desempenho.
     */
    function listData(Request $request)
    {
        $service = new DisciplinePerformanceDataService();
        $data =  $service->getPerformanceData($request['disciplineCode'], $request['year'], $request['period'], 10);
        return view('discipline_performance_data.performance_data_index')
            ->with('theme', $this->theme)
            ->with('performanceData', $data)
            ->with('disciplineCode', $request->disciplineCode)
            ->with('disciplineName', $request->disciplineName)
            ->with('year', $request->year)
            ->with('period', $request->period);
    }

    /**
     * @brief Exclui os dados de desempenho da disciplina.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página anterior.
     */
    function deletePerformanceData(Request $request)
    {

        $service = new DisciplinePerformanceDataService();
        $idData = $request->idData;
        $service->deletePerformanceData($idData);
        return redirect()->back();
    }

    /**
     * @brief Exclui os dados de desempenho da disciplina com base no código da disciplina, ano e período.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página anterior.
     */
    function deletePerformanceDataByCodeYearPeriod(Request $request)
    {
        try {
            $this->performanceDataService->deletePerformanceDataByCodeYearPeriod($request->disciplineCode, $request->year, $request->period);
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['delete' => 'Erro ao deletar']);
        }
    }
}
