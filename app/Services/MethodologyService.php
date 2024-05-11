<?php

namespace App\Services;

use App\Exceptions\ExistingDataException;
use App\Exceptions\NotAuthorizedException;
use App\Models\Discipline;
use App\Models\Methodology;
use App\Models\ProfessorMethodology;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MethodologyService
{
    public function listAllMethodologies()
    {
        return Methodology::query()->orderBy('name')->get();
    }

    public function saveMethodology($name, $description, $idProfessor)
    {
        $methodology = new Methodology();
        $methodology->name = $name;
        $methodology->description = $description;
        $methodology->{'professor_id'} = $idProfessor;
        return $methodology->save();
    }

    public function getProfessorMethodologies($professorId, $disciplineId)
    {
        $discipline = Discipline::find($disciplineId);
        $professorMethodologies = $discipline->professor_methodologies()
            ->where('professor_methodologies.professor_id', '=', $professorId)
            ->select(
                'professor_methodologies.id',
                'professor_methodologies.professor_id as professor_methodology_id',
                'professor_methodologies.methodology_use_description as methodology_use_description',
                'methodology_id',
                'methodologies.name as methodology_name',
                'methodologies.professor_id as methodology_owner',
                'methodologies.description as methodology_description',
            )
            ->join('methodologies', 'methodologies.id', '=', 'professor_methodologies.methodology_id')->orderBy('methodology_name')->get();
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

    public function updateProfessorMethodology($idProfessorMethodology, $description, $disciplineCode)
    {
        $professorMethodology = ProfessorMethodology::find($idProfessorMethodology);
        if (Auth::user()->isAdmin || ($professorMethodology->professor_id == Auth::user()->professor->id)) {
            $professorMethodology = ProfessorMethodology::find($idProfessorMethodology);
            $professorMethodology->methodology_use_description = $description;
            $professorMethodology->$disciplineCode;
            $professorMethodology->save();
            return $professorMethodology;
        } else {
            throw new NotAuthorizedException();
        }
    }

    public function addMethodologiesToDiscipline($idMethodology, $idDiscipline)
    {
        $professorId = Auth::user()->professor->id;
        $discipline = Discipline::findOrFail($idDiscipline);
        $methodology = Methodology::findOrFail($idMethodology);
        $professorMethodologyQuery = $discipline->professor_methodologies()->where('methodology_id', '=', $idMethodology)->where('professor_id','=',$professorId);
        if ($professorMethodologyQuery->exists()) {
            $professorMethodology = $professorMethodologyQuery->first();
            $disciplinesWithProfessorMethodology = $professorMethodology->disciplines();
            if ($disciplinesWithProfessorMethodology->where('disciplines.id', '=', $idDiscipline)->exists()) {
                throw new ExistingDataException('Já esiste esta metodologia na disciplina');
            }
            $professorMethodology->disciplines()->attach($idDiscipline);
        } else {
            $newProfessorMethodology = ProfessorMethodology::create([
                'professor_id' => $professorId,
                'methodology_id' => $methodology->id

            ]);
            $discipline->professor_methodologies()->attach($newProfessorMethodology->id);
        }
    }

    function deleteMethodology($idMethodology)
    {
        if (Auth::user() && Auth::user()->isProfessor) {
            $professorId = Auth::user()->professor->id;
            $professorMethodology = ProfessorMethodology::where('methodology_id', '=', $idMethodology)->first();
            if ($professorMethodology != null) {
                if (!$professorMethodology->disciplines()->exists()) {
                    $professorMethodology->delete();
                    Methodology::find($idMethodology)->delete();
                } else {
                    $canDelete = true;
                    $disciplinesWithThisMethodology = $professorMethodology->disciplines;
                    foreach ($disciplinesWithThisMethodology as $discipline) {
                        if ($discipline->professor->id != $professorId) {
                            $canDelete = false;
                            break;
                        }
                    }
                    if ($canDelete) {
                        foreach ($disciplinesWithThisMethodology as $discipline) {
                            $discipline->professor_methodologies()->detach($professorMethodology->id);
                        }
                        $professorMethodology->delete();
                        return Methodology::find($idMethodology)->delete();
                    } else {
                        throw new NotAuthorizedException('Não é possível apagar esta metodologia. Disciplinas de outros professores estão usando.');
                    }
                }
            } else {
                $methodology = Methodology::find($idMethodology);
                if ($methodology->professor_id == $professorId) {
                    return $methodology->delete();
                } else {
                    throw new NotAuthorizedException('Você não tem permissão para executar esta operação.');
                }
            }
        } else if (Auth::user() && Auth::user()->isAdmin) {
            $professorMethodologies = ProfessorMethodology::where('methodology_id', '=', $idMethodology);
            if (!$professorMethodologies->exists()) {
                return Methodology::find($idMethodology)->delete();
            } else {
                throw new NotAuthorizedException('Não é possível apagar esta metodologia. Disciplinas de outros professores estão usando.');
            }
        }
    }

    function deleteProfessorMethodology($idProfessorMethodology)
    {
        if (Auth::user() && Auth::user()->professor) {
            $professorMethodology = ProfessorMethodology::findOrFail($idProfessorMethodology);
            if ($professorMethodology->{'professor_id'} != Auth::user()->professor->id) {
                throw new NotAuthorizedException('Você não tem permissão para executar esta operação.');
            } else {
                $professorMethodology->delete();
                return $professorMethodology;
            }
        } else {
            throw new NotAuthorizedException('Você não tem permissão para executar esta operação.');
        }
    }

    function removeProfessorMethodologyFromDiscipline($disciplineId, $professorMethodologyId)
    {
        $professor = Auth::user()->professor;
        $professorMethodology = ProfessorMethodology::findOrFail($professorMethodologyId);
        $discipline = Discipline::findOrFail($disciplineId);
        if (($professor->id != $professorMethodology->professor->id) || ($professor->id != $discipline->professor->id)) {
            throw new NotAuthorizedException("Você não tem autorização para realizar esta operação");
        }
        $discipline->professor_methodologies()->detach($professorMethodologyId);
        return $professorMethodology;
    }
}