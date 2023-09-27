<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\DisciplinePerfomanceData;
use App\Services\DisciplinePerformanceDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchedulingDisciplinePerformanceUpdateController extends Controller
{

    protected $theme;
    
    function __construct(){
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    function index(Request $request){

        $scheduleService = new DisciplinePerformanceDataService();
        $schedules = $scheduleService->listAll();

        return view('discipline_performance_data.schedules_index')
        ->with('theme',$this->theme)
        ->with('schedules', $schedules);
    }

    function store(Request $request){

        $service = new DisciplinePerformanceDataService();
        $data = $request->only('disciplineCode', 'year', 'period');
        $service->save($data);

        return redirect()->route('scheduling.index');
    }

    function delete(Request $request){
        $service = new DisciplinePerformanceDataService();
        $service->delete($request['idSchedule']);
        return redirect()->route('scheduling.index');

    }


}
