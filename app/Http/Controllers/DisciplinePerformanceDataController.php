<?php

namespace App\Http\Controllers;

use App\Models\DisciplinePerformanceData;
use App\Services\DisciplinePerformanceDataService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DisciplinePerformanceDataController extends Controller
{
    protected $performanceDataService;
    protected $theme;
    function __construct(){
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->performanceDataService = new DisciplinePerformanceDataService();
    }

    /**
     * Retorna os indices de perfomance da displina no formato JSON
     * 
     */
    function getDisciplinePerformanceData(Request $request){

        $service = new DisciplinePerformanceDataService();
        $datas = $service->getPerformanceData($request['disciplineCode'], $request['year'], $request['period']);
        return response()->json($datas);
    }

    function index(Request $request){
        
        $service = new DisciplinePerformanceDataService(); $service = new DisciplinePerformanceDataService();
        $data =  $service->getPerformanceData($request['disciplineCode'], $request['year'], $request['period']);
        return view('discipline_performance_data.performance_data_index')
            ->with('theme', $this->theme)
            ->with('performanceData', $data)
            ->with('disciplineCode',$request->disciplineCode)
            ->with('disciplineName', $request->disciplineName)
            ->with('year', $request->year)
            ->with('period', $request->period);

    }

    function deletePerformanceData(Request $request){

        $service = new DisciplinePerformanceDataService();
        $idData = $request->idData;
        $service->deletePerformanceData($idData);
        return redirect()->back();
    }

    function deletePerformanceDataByCodeYearPeriod(Request $request){
        try{
            $this->performanceDataService->deletePerformanceDataByCodeYearPeriod($request->disciplineCode, $request->year, $request->period);
            return redirect()->back();
        }catch(Exception $e){
           return redirect()->back()->withErrors(['delete'=>'Erro ao deletar']);
        }
        

    }





}
