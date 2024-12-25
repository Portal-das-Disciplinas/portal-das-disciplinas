<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    public function index(Request $request)
    {
        $courseService = new CourseService();
        if (Auth::user() && Auth::user()->is_admin) {
            $courses = $courseService->list();
            
        } elseif ($this->checkProfessorWithUnit()) {
                $professor_id = Auth::user()->professor->institutionalUnit->id;
                $courses = $courseService->list($unitId = $professor_id);
            
        }
        return view('course/index', [
            'courses' => $courses

        ])->with('theme', $this->theme);
    }

    private function checkProfessorWithUnit(){
        return (Auth::user() && Auth::user()->is_professor 
            && (isset(Auth::user()->professor->institutionalUnit)));
    }
}
