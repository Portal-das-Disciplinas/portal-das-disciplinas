<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidIntervalException;
use App\Models\Discipline;
use App\Models\DisciplinePerfomanceData;
use App\Services\DisciplinePerformanceDataService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SchedulingDisciplinePerformanceUpdateController extends Controller
{

    protected $theme;

    function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin');
    }

    function index(Request $request)
    {
        $statusBeforeDelete = Session::get('statusBeforeDelete');
        
        if(isset($statusBeforeDelete)){
            $searchType = $statusBeforeDelete;
        }else{
            $searchType = $request->scheduleStatus;
        }
       
        $scheduleService = new DisciplinePerformanceDataService();
        if (!isset($searchType)) {
            $searchType = "PENDING";
        }
        $paginateValue = 10;
        $schedules = $scheduleService->listSchedules($searchType, $paginateValue);

        return view('discipline_performance_data.schedules_index')
            ->with('theme', $this->theme)
            ->with('schedules', $schedules)
            ->with('searchType', $searchType);
    }

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

    function delete(Request $request)
    {
        $service = new DisciplinePerformanceDataService();
        $service->delete($request['idSchedule']);
        return redirect()->route('scheduling.index')->with(['statusBeforeDelete'=>$request->searchType]);
    }

    function runSchedule(Request $request)
    {
        $service = new DisciplinePerformanceDataService();
        $service->runSchedule($request->idSchedule);
        return redirect()->route('scheduling.index');
    }
}
