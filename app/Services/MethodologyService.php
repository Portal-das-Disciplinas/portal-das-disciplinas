<?php

namespace App\Services;

use App\Exceptions\NotAuthorizedException;
use App\Models\Methodology;
use App\Models\ProfessorMethodology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function update($idMethodology, $name, $description)
    {
        $methodology = Methodology::find($idMethodology);
        if (Auth::user()->isAdmin || ($methodology->professor_id == Auth::user()->professor->id)) {
            $methodology = Methodology::find($idMethodology);
            $methodology->name = $name;
            $methodology->description = $description;
            $methodology->save();
            return $methodology;
        } else {
            throw new NotAuthorizedException();
        }
    }

    public function updateProfessorMethodology($idProfessorMethodology, $description)
    {
        $professorMethodology = ProfessorMethodology::find($idProfessorMethodology);
        if (Auth::user()->isAdmin || ($professorMethodology->professor_id == Auth::user()->professor->id)) {
            $professorMethodology = ProfessorMethodology::find($idProfessorMethodology);
            $professorMethodology->description = $description;
            $professorMethodology->save();
            return $professorMethodology;
        } else{
            throw new NotAuthorizedException();
        }
    }
}
