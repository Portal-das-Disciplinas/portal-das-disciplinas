<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAuthorizedException;
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
        $courses = [];
        $courseService = new CourseService();
        if (Auth::user() && Auth::user()->is_admin) {
            $courses = $courseService->list();
            
        } elseif ($this->checkProfessorWithUnit()) {
                $professor_unit_id = Auth::user()->professor->institutionalUnit->id;
                $courses = $courseService->list($unitId = $professor_unit_id);
            
        }elseif($this->checkIsUnitAdmin()){
            $adminUnitId = Auth::user()->unitAdmin->institutionalUnit->id;
            $courses = $courseService->list($unitId = $adminUnitId);
        }

        return view('course/index', [
            'courses' => $courses

        ])->with('theme', $this->theme);
    }

    public function store(Request $request){
        if(!$this->checkIsAdminOrUnitAdmin()){

            return redirect()->back()->withErrors([
                'store_error' => 'Você não tem permissão para realizar esta operação.'
            ]);
        }

        $courseService = new CourseService();
        try{
            $courseService->save($request->{'course-name'}, $request->{'unit-id'}, $request->{'level-id'});
            return redirect()->back()->with('success_message','Curso cadastrado com sucesso.');

        }catch(NotAuthorizedException $e1){
            return redirect()->back()->withErrors(['store_error' => $e1->getMessage()]);

        }catch(Exception $e2){
            return redirect()->back()->withErrors(['store_error' => "Não foi possível cadastrar."]);
        }
        
    }

    private function checkProfessorWithUnit(){
        return (Auth::user() && Auth::user()->is_professor 
            && (isset(Auth::user()->professor->institutionalUnit)));
    }

    private function checkIsUnitAdmin(){
        return Auth::user() && Auth::user()->is_unit_admin;
    }

    private function checkIsAdminOrUnitAdmin(){
        return Auth::user() && (Auth::user()->is_admin || Auth::user()->is_unit_admin);
    }


}
