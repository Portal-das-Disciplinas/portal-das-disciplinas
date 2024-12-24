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
        $this->middleware('admin')->except(['index']);
    }

    public function index(Request $request)
    {
        $courseLevelService = new CourseLevelService();
        $courseLevels = $courseLevelService->list();

        return view('course_level/index', [
            'courseLevels' => $courseLevels
        ])->with('theme', $this->theme);
    }

    public function store(Request $request)
    {
        $courseLevelService = new CourseLevelService();
        $courseLevelService->save($request->value, $request->{'priority-level'});
        return redirect()->back()->with([
            'success_message' => 'Cadastrado com sucesso'
        ]);
    }
}
