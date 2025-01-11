<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAuthorizedException;
use App\Services\CourseLevelService;
use App\Services\CourseService;
use App\Services\EducationLevelService;
use App\Services\InstitutionalUnitService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    protected $theme;
    protected $educationLevelService;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->educationLevelService = new EducationLevelService();
    }

    public function index(Request $request)
    {
        $courses = [];
        $courseService = new CourseService();
        $unitService = new InstitutionalUnitService();
        $institutionalUnits = [];
        if (Auth::user() && Auth::user()->is_admin) {
            $courses = $courseService->list();
            $institutionalUnits = $unitService->listAll();
            
        }elseif($this->checkIsUnitAdmin()){
            $adminUnitId = Auth::user()->unitAdmin->institutionalUnit->id;
            $institutionalUnits = collect();
            $institutionalUnit = $unitService->getByUnitAdmin(Auth::user()->unitAdmin->id);
            $institutionalUnits->add($institutionalUnit);
            $courses = $courseService->list($unitId = $adminUnitId);
        }
        
        $educationLevels = $this->educationLevelService->list();

        return view('course/index', [
            'courses' => $courses,
            'institutionalUnits' => $institutionalUnits,
            'educationLevels' => $educationLevels

        ])->with('theme', $this->theme);
    }

    public function store(Request $request){
        if(!$this->checkIsAdminOrUnitAdmin()){
            return redirect()->back()->withErrors([
                'auth_error' => 'Você não tem permissão para realizar esta operação.'
            ]);
        }
        $courseService = new CourseService();
        try{
            $courseService->save($request->{'course-name'}, $request->{'unit-id'}, $request->{'education-level-id'});
            return redirect()->back()->with('success_message','Curso cadastrado com sucesso.');

        }catch(NotAuthorizedException $e1){
            Log::error($e1->getMessage());
            return redirect()->back()->withErrors(['store_error' => $e1->getMessage()]);

        }catch(Exception $e2){
            Log::error($e2->getMessage());
            return redirect()->back()->withErrors(['store_error' => "Não foi possível cadastrar."]);
        }
        
    }

    private function checkIsUnitAdmin(){
        return Auth::user() && Auth::user()->is_unit_admin;
    }

    private function checkIsAdminOrUnitAdmin(){
        return Auth::user() && (Auth::user()->is_admin || Auth::user()->is_unit_admin);
    }


}
