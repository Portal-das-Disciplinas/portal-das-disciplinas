<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidIntervalException;
use App\Models\Discipline;
use App\Models\DisciplinePerfomanceData;
use App\Services\DisciplinePerformanceDataService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @class SchedulingDisciplinePerformanceUpdateController
 * @brief Controlador para agendar e gerenciar a atualização de dados de desempenho de disciplinas.
 */
class SchedulingDisciplinePerformanceUpdateController extends Controller
{

    protected $theme;

    function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin');
    }

    /**
     * @brief Exibe a página inicial de agendamento de atualizações de desempenho de disciplinas.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\View\View Retorna a view 'discipline_performance_data.schedules_index'.
     */
    function index(Request $request)
    {
        $paginateValue = 10;
        $status = $request->scheduleStatus;
        $scheduleService = new DisciplinePerformanceDataService();
        $searchType = "TODOS";
        if ($status == null) {
            $schedules = $scheduleService->listSchedules('PENDING', $paginateValue);
            $searchType = "PENDENTES";
        } else {
            $schedules = $scheduleService->listSchedules($status, $paginateValue);
            switch ($status) {
                case 'PENDING':
                    $searchType = 'PENDENTES';
                    break;
                case 'RUNNING':
                    $searchType = 'EXECUTANDO';
                    break;
                case 'COMPLETE':
                    $searchType = 'COMPLETOS';
                    break;
                case 'ERROR':
                    $searchType = 'COM ERROS';
                    break;
                default:
                    $searchType = '';
            }
        }

        return view('discipline_performance_data.schedules_index')
            ->with('theme', $this->theme)
            ->with('schedules', $schedules)
            ->with('searchType', $searchType);
    }

    /**
     * @brief Armazena um novo agendamento de atualização de desempenho de disciplinas.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página de agendamento.
     */
    function store(Request $request)
    {
        try {
            $service = new DisciplinePerformanceDataService();
            $data = $request->only('disciplineCode', 'yearStart', 'periodStart', 'yearEnd', 'periodEnd', 'updateIfExists');
            $service->saveSchedules($data);
            return redirect()->route('scheduling.index');
        } catch (InvalidIntervalException $e1) {
            Log::error($e1->getMessage());
            return redirect()->back()->withErrors(["interval_error" => $e1->getMessage()]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(["schedule_error" => "Não foi possível cadastrar o agendamento"]);
        }
    }

    /**
     * @brief Exclui um agendamento de atualização de desempenho de disciplinas.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página de agendamento.
     */
    function delete(Request $request)
    {
        $service = new DisciplinePerformanceDataService();
        $service->delete($request['idSchedule']);
        return redirect()->route('scheduling.index');
    }
    
    /**
     * @brief Executa um agendamento de atualização de desempenho de disciplinas.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse Redireciona de volta para a página de agendamento.
     */
    function runSchedule(Request $request)
    {
        $service = new DisciplinePerformanceDataService();
        $service->runSchedule($request->idSchedule);
        return redirect()->route('scheduling.index');
    }
}
