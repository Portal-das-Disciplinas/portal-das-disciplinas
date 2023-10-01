<?php

namespace App\Http\Controllers;

use App\Services\DisciplinePerformanceDataService;
use Illuminate\Http\Request;

class DisciplinePerformanceDataController extends Controller
{
    function getDisciplinePerformanceData(Request $request){

        $service = new DisciplinePerformanceDataService();
        $datas = $service->getPerformanceData($request['disciplineCode'], $request['year'], $request['period']);
        return response()->json($datas);
    }
}
