<?php

namespace App\Http\Controllers;

use App\Models\SemesterPerformanceData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SemesterPerformanceDataController extends Controller
{
    protected $theme;
    function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true); 
    }

    public function index(){

        $semesterPerformanceData = SemesterPerformanceData::query()->orderBy('year')->orderBy('period')->get();
        return view('discipline_performance_data/semester_performance_data')
            ->with('theme',$this->theme)
            ->with('semesterPerformanceData',$semesterPerformanceData);
    }
}
