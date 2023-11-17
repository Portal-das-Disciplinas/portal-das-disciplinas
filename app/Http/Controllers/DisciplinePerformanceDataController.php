<?php

namespace App\Http\Controllers;

use App\Models\DisciplinePerformanceData;
use App\Services\DisciplinePerformanceDataService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stdClass;

class DisciplinePerformanceDataController extends Controller
{
    protected $performanceDataService;
    protected $theme;
    function __construct(){
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->performanceDataService = new DisciplinePerformanceDataService();
        $this->middleware('admin')->except(['getDisciplinePerformanceData','getDisciplinePerformanceDataByInterval']);
    }

    /**
     * Retorna os indices de perfomance da displina no formato JSON
     * 
     */
    function getDisciplinePerformanceData(Request $request){
        try{
            $service = new DisciplinePerformanceDataService();
            $datas = $service->getPerformanceData($request['disciplineCode'], $request['year'], $request['period']);
            return response()->json($datas);
        }catch(Exception $e){
            $error = new stdClass();
            $error->error = "Erro";
            return response()->json(json_encode($error));
        }
        
    }

    function getDisciplinePerformanceDataByInterval(Request $request){
        Log::info('year start ' . $request['yearStart']);
        $service = new DisciplinePerformanceDataService();
        try{
            $datas = $service->getPerformanceDataByInterval($request['disciplineCode'], $request['yearStart'],
                                $request['periodStart'],$request['yearEnd'],$request['periodEnd']);
            Log::info(count($datas));
            return response()->json($datas);
        }catch(Exception $e){
            $error = new stdClass();
            $error->error = "Erro";
            Log::error("error" . $e->getMessage());
            return response()->json(json_encode($error));
        }
    }

    function index(Request $request){
        return view('discipline_performance_data.performance_data_index')->with('theme', $this->theme); 

    }

    function listData(Request $request){
        $service = new DisciplinePerformanceDataService(); 
        $data =  $service->getPerformanceData($request['disciplineCode'], $request['year'], $request['period'],10);
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
