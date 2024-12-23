<?php

namespace App\Http\Controllers;

use App\Services\CourseLevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseLevelController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    public function index(Request $request)
    {
        $courseLevelService = new CourseLevelService();
        $courseLevels = $courseLevelService->list();
        
        return view('course_level/index', [
            'courseLevels' => $courseLevels
        ])->with('theme', $this->theme);
    }
}
