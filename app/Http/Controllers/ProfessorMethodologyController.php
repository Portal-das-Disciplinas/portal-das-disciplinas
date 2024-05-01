<?php

namespace App\Http\Controllers;

use App\Services\MethodologyService;
use Illuminate\Http\Request;

class ProfessorMethodologyController extends Controller
{
    public function listProfessorMethodologies(Request $request){
        $methodologyService = new MethodologyService();
        if($request->ajax()){
            $professorMethodologies = $methodologyService->getProfessorMethodologies($request['id_professor'],$request['codigo_disciplina']);
            return response()->json($professorMethodologies->toArray());
        }
    }

    public function update(Request $request){
        $methodologyService = new MethodologyService();
        $description = isset($request->description) ? $request->description : "";
        $professorMethodology = $methodologyService->updateProfessorMethodology($request->idProfessorMethodology, $description);
        if($request->ajax()){
            return response()->json($professorMethodology);
        }

    } 
}
