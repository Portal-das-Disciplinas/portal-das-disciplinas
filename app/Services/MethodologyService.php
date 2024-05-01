<?php

namespace App\Services;

use App\Models\Methodology;
use App\Models\ProfessorMethodology;

class MethodologyService
{

    public function listAll()
    {
        return Methodology::all();
    }

    public function getProfessorMethodologies($professorId, $disciplineCode)
    {
        $professorMethodologies = ProfessorMethodology::query()
            ->select(
                'professor_methodologies.id',
                'professor_methodologies.professor_id as professor_methodology_id',
                'professor_methodologies.description as professor_description',
                'methodology_id',
                'methodologies.name as methodology_name',
                'methodologies.description as methodology_description'
            )
            ->join('methodologies', 'methodologies.id', '=', 'professor_methodologies.methodology_id')
            ->where('professor_methodologies.professor_id', '=', $professorId)
            ->where('methodologies.discipline_code', '=', $disciplineCode)->get();
        return $professorMethodologies;
    }
}
