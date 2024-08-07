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
use LengthException;

class MethodologyService
{
    public function listAllMethodologies()
    {
        return Methodology::query()->orderBy('name')->get();
    }

    public function saveMethodology($name, $description, $idProfessor = null)
    {
        if (!isset($name) || strlen($name) < 3) {
            throw new LengthException('Nome da disciplina muito curto.');
        }
        if (!isset($description) || strlen($description) < 3) {
            throw new LengthException('Descrição da metodologia muito curta.');
        }

        $methodology = new Methodology();
        if (Methodology::where('name', '=', $name)->exists()) {
            throw new ExistingDataException('Já existe uma metodologia com este nome cadastrada.');
        }
        $methodology->name = $name;
        $methodology->description = $description;

        if (!Auth::user()->isAdmin) {
            $methodology->{'professor_id'} = $idProfessor;
        }
      
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
                'professor_methodologies.professor_description'
            )
            ->join('methodologies', 'methodologies.id', '=', 'professor_methodologies.methodology_id')->orderBy('methodology_name')->get();
        return $professorMethodologies;
    }

    public function update($idMethodology, $name, $description)
    {
        if (!isset($name) || strlen($name) < 3) {
            throw new LengthException('Nome da disciplina muito curto.');
        }
        if (!isset($description) || strlen($description) < 3) {
            throw new LengthException('Descrição da metodologia muito curta.');
        }

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

    public function updateProfessorMethodology($idProfessorMethodology, $description, $professor_methodology_description)
    {
        $professorMethodology = ProfessorMethodology::find($idProfessorMethodology);
        if (Auth::user()->isAdmin || ($professorMethodology->professor_id == Auth::user()->professor->id)) {
            $professorMethodology = ProfessorMethodology::find($idProfessorMethodology);
            $professorMethodology->methodology_use_description = $description;
            $professorMethodology->professor_description = $professor_methodology_description;
            $professorMethodology->save();
            return $professorMethodology;
        } else {
            throw new NotAuthorizedException();
        }
    }

    public function addMethodologiesToDiscipline($idProfessor, $idMethodology, $idDiscipline)
    {
        if (Auth::user() && Auth::user()->isAdmin) {
            $professorId = $idProfessor;
        } elseif (Auth::user() && Auth::user()->isProfessor && Auth::user()->professor->id == $idProfessor) {
            $professorId = Auth::user()->professor->id;
        } else {
            throw new NotAuthorizedException("Não autorizado.");
        }
        $discipline = Discipline::findOrFail($idDiscipline);
        $methodology = Methodology::findOrFail($idMethodology);
        $professorMethodologyQuery = ProfessorMethodology::where('professor_id', $professorId)->where('methodology_id', '=', $idMethodology);
        if ($professorMethodologyQuery->exists()) {
            $professorMethodology = $professorMethodologyQuery->first();
            $disciplinesWithProfessorMethodology = $professorMethodology->disciplines();
            if ($disciplinesWithProfessorMethodology->where('disciplines.id', '=', $idDiscipline)->exists()) {
                throw new ExistingDataException('Já esiste esta metodologia na disciplina');
            }
            $professorMethodology->disciplines()->attach($idDiscipline);
            return $professorMethodology;
        } else {
            $newProfessorMethodology = ProfessorMethodology::create([
                'professor_id' => $professorId,
                'methodology_id' => $methodology->id

            ]);
            $discipline->professor_methodologies()->attach($newProfessorMethodology->id);
            return $newProfessorMethodology;
        }
    }

    function deleteMethodology($idMethodology)
    {
        if (Auth::user() && Auth::user()->isProfessor) {
            $professorId = Auth::user()->professor->id;
            $methodology = Methodology::findOrFail($idMethodology);
            if ($methodology->professor_id != $professorId) {
                throw new NotAuthorizedException('Você não tem permissão para realizar esta operação');
            }
            $professorMethodologyQuery = ProfessorMethodology::where('methodology_id', '=', $idMethodology);
            if ($professorMethodologyQuery->exists()) {
                $linkedToDiscipline = false;
                foreach ($professorMethodologyQuery->get() as $professorMethodology) {
                    if ($professorMethodology->disciplines()->count() > 0) {
                        $linkedToDiscipline = true;
                        break;
                    }
                }
                if ($linkedToDiscipline == false) {
                    $professorMethodologyQuery->delete();
                    return $methodology->delete();
                } else {
                    $canDelete = true;
                    foreach ($professorMethodologyQuery->get() as $professorMethodology) {
                        foreach ($professorMethodology->disciplines()->get() as $discipline) {
                            if ($discipline->professor->id != $professorId) {
                                $canDelete = false;
                                break;
                            }
                        }
                    }
                    if ($canDelete) {
                        $professorMethodologyQuery->delete();
                        return $methodology->delete();
                    } else {
                        throw new NotAuthorizedException('Não é possível apagar esta metodologia. Disciplinas de outros professores estão usando.');
                    }
                }
            } else {
                return $methodology->delete();
            }
        } else if (Auth::user() && Auth::user()->isAdmin) {
            ProfessorMethodology::where('methodology_id', '=', $idMethodology)->delete();
            return Methodology::find($idMethodology)->delete();
        } else {
            return new NotAuthorizedException("Usuário não logado.");
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
        if (Auth::user() && Auth::user()->isAdmin) {
            $professorMethodology = ProfessorMethodology::findOrFail($professorMethodologyId);
            $discipline = Discipline::findOrFail($disciplineId);
            $discipline->professor_methodologies()->detach($professorMethodologyId);
        } elseif (Auth::user() && Auth::user()->isProfessor) {
            $professor = Auth::user()->professor;
            $professorMethodology = ProfessorMethodology::findOrFail($professorMethodologyId);
            $discipline = Discipline::findOrFail($disciplineId);
            if (($professor->id != $professorMethodology->professor->id) || ($professor->id != $discipline->professor->id)) {
                throw new NotAuthorizedException("Você não tem autorização para realizar esta operação");
            }
            $discipline->professor_methodologies()->detach($professorMethodologyId);
        }

        return $professorMethodology;
    }
}
