<?php

namespace App\Services;

use App\Exceptions\NotAuthorizedException;
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

    public function getProfessorMethodologies($professorId, $disciplineCode)
    {
        $professorMethodologies = ProfessorMethodology::query()
            ->select(
                'professor_methodologies.id',
                'professor_methodologies.professor_id as professor_methodology_id',
                'professor_methodologies.description as professor_description',
                'methodology_id',
                'methodologies.name as methodology_name',
                'methodologies.description as methodology_description',
                'professor_methodologies.discipline_code'
            )
            ->join('methodologies', 'methodologies.id', '=', 'professor_methodologies.methodology_id')
            ->where('professor_methodologies.professor_id', '=', $professorId)
            ->where('professor_methodologies.discipline_code', '=', $disciplineCode)->orderBy('methodologies.name')->get();
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
            $professorMethodology->description = $description;
            $professorMethodology->$disciplineCode;
            $professorMethodology->save();
            return $professorMethodology;
        } else {
            throw new NotAuthorizedException();
        }
    }

    public function addMethodologiesToProfessorDiscipline($idMethodology, $idProfessor, $disciplineCode)
    {
        $methodology = Methodology::findOrFail($idMethodology);
        if (count(ProfessorMethodology::where('methodology_id', '=', $methodology->id)->where('professor_id', '=', Auth::user()->professor->id)->get()) == 0) {
            ProfessorMethodology::create([
                'methodology_id' => $methodology->id,
                'professor_id' => $idProfessor,
                'discipline_code' => $disciplineCode,
                'description' => ""
            ]);
        }
    }

    function deleteMethodology($idMethodology)
    {
        DB::beginTransaction();
        try {
            $methodology = Methodology::findOrFail($idMethodology);
            if ($methodology->{'professor_id'} == null) {
                if (Auth::user()->isAdmin) {
                    DB::beginTransaction();
                    $professorMethodologies = ProfessorMethodology::where('methodology_id', '=', $idMethodology)->get();
                    $numMethodologiesProfessor = count($professorMethodologies);
                    if ($numMethodologiesProfessor == 0) {
                        $methodology->delete();
                    } else {
                        throw new NotAuthorizedException("Há disciplinas que usam esta metodologia.");
                    }
                } else {
                    throw new NotAuthorizedException("Você não tem autorização para deletar esta metodologia.");
                }
            } else if ($methodology->{'professor_id'} == Auth::user()->professor->id) {
                $professorMethodologies = ProfessorMethodology::where('methodology_id', '=', $idMethodology)->get();
                $numMethodologiesProfessor = count($professorMethodologies);
                if ($numMethodologiesProfessor == 0) {
                    $methodology->delete();
                } else {
                    $numThisProfessorMethodologies = count(ProfessorMethodology::where('methodology_id', '=', $idMethodology)
                        ->where('professor_id', '=', Auth::user()->professor->id)->get());
                    if ($numThisProfessorMethodologies == $numMethodologiesProfessor) {
                        foreach ($professorMethodologies as $professorMethodology) {
                            $professorMethodology->delete();
                        }
                        $methodology->delete();
                    } else {
                        throw new NotAuthorizedException("Há disciplinias que usam esta metodologia.");
                    }
                }
            } else {
                throw new NotAuthorizedException("Você não está autorizado a deletar esta metodologia.");
            }
            DB::commit();
            return $methodology;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
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
}
