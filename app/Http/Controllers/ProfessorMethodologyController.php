<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAuthorizedException;
use App\Services\MethodologyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfessorMethodologyController extends Controller
{
    public function listProfessorMethodologies(Request $request)
    {
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            $professorMethodologies = $methodologyService->getProfessorMethodologies($request['id_professor'], $request['codigo_disciplina']);
            return response()->json($professorMethodologies->toArray());
        }
    }

    public function update(Request $request)
    {
        $methodologyService = new MethodologyService();
        $description = isset($request->description) ? $request->description : "";
        $professorMethodology = $methodologyService
            ->updateProfessorMethodology($request->idProfessorMethodology, $description, $request->discipline_code);
        if ($request->ajax()) {
            return response()->json($professorMethodology);
        }
    }

    public function addProfessorMethodologies(Request $request)
    {
        $arrayMethodologies = $request->methodologies_array;
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            foreach ($arrayMethodologies as $methodology) {
                $methodologyService
                    ->addMethodologiesToProfessorDiscipline($methodology['id'], $methodology['professor_methodology_id'], $methodology['discipline_code']);
            }
        }
    }

    public function destroy(Request $request)
    {
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            try {
                $deleted = $methodologyService->deleteProfessorMethodology($request->{'id_professor_methodology'});
                return response()->json($deleted);
            } catch (NotAuthorizedException $e) {
                return response()->json(['error' => $e->getMessage()], 403);
            }
        }
    }
}
