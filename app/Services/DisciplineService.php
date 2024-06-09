<?php

namespace App\Services;

use App\Models\Classification;
use App\Models\ClassificationDiscipline;
use App\Models\Discipline;
use App\Models\DisciplinePerformanceData;
use App\Models\Professor;
use App\Models\ProfessorMethodology;
use App\Models\SubjectConcept;
use App\Models\SubjectReference;
use App\Models\SubjectTopic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Normalizer;

class DisciplineService
{
    public function filterDisciplines(Request $request)
    {

        $filteredDisciplines = collect([]);
        $disciplines = Discipline::query();
        $disciplines->leftJoin('subject_topics', 'disciplines.id', '=', 'subject_topics.discipline_id')
            ->leftJoin('subject_concepts', 'disciplines.id', '=', 'subject_concepts.discipline_id')
            ->leftJoin('subject_references', 'disciplines.id', '=', 'subject_references.discipline_id')
            ->select('disciplines.*');
        if ($request->name_discipline) {
            $searchValues = array_map('trim', explode(',', $request->name_discipline));
            $disciplines->where(function (Builder $query) use ($searchValues) {
                foreach ($searchValues as $value) {
                    $query->orWhere('name', 'like', '%' . $value . '%');
                    $query->orWhere('subject_topics.value', 'like', '%' . $value . '%');
                    $query->orWhere('subject_concepts.value', 'like', '%' . $value . '%');
                    $query->orWhere('subject_references.value', 'like', '%' . $value . '%');
                }
            });
        } else {
            $disciplines->where('name', 'like', '%' . "" . '%');
        }
        if ($request->emphasis) {
            $disciplines->where('emphasis_id', $request->emphasis);
        }
        if ($request->professors && $request->professors != "null") {
            $disciplines->where('professor_id', $request->professors);
        }
        $filteredDisciplines = $disciplines->orderBy('name', 'asc')->get();

        /*if ($request->{'filtro-livre'}) {
            $filteredByCustomFilter = collect([]);
            $requestText = $request->{'filtro-livre'};
            $words = explode(",", $requestText);
            foreach ($filteredDisciplines as $discipline) {
                $topicsCount = SubjectTopic::where('discipline_id', '=', $discipline->id);
                $conceptsCount = SubjectConcept::where('discipline_id', '=', $discipline->id);
                $referencesCount = SubjectReference::where('discipline_id', '=', $discipline->id);
                foreach ($words as $word) {
                    $topicsCount->where('value', 'like', '%' . $word . '%');
                    $conceptsCount->where('value', 'like', '%' . $word . '%');
                    $referencesCount->where('value', 'like', '%' . $word . '%');
                }
                if (($topicsCount->count() + $conceptsCount->count() + $referencesCount->count()) > 0) {
                    $filteredByCustomFilter->push($discipline);
                }
            }
            $filteredDisciplines = $filteredByCustomFilter;
        } */

        if ($request->{'filtered-methodologies'}) {
            $filterByMethodologies = collect([]);
            $methodologies = json_decode($request->{'filtered-methodologies'});
            foreach ($filteredDisciplines as $filteredDiscipline) {
                $includeToArray = true;
                foreach ($methodologies as $methodology) {
                    $professorMethodologies = $filteredDiscipline->professor_methodologies()->where('methodology_id', '=', $methodology->id);
                    if ($professorMethodologies->count() == 0) {
                        $includeToArray = false;
                        break;
                    }
                }
                if ($includeToArray == true) {
                    $filterByMethodologies->push($filteredDiscipline);
                }
            }
            $filteredDisciplines = $filterByMethodologies;
        }

        if ($request->{'check-filtro-classificacoes'} && $request->{'check-filtro-classificacoes'} == "on") {
            $filteredByClassifications = collect([]);
            foreach ($filteredDisciplines as $discipline) {
                $classifications = ClassificationDiscipline::where('discipline_id', '=', $discipline->id)
                    ->join('classifications', 'classification_id', '=', 'classifications.id')
                    ->select('classifications.id as id_classification', 'classifications.name', 'classifications_disciplines.value')->get();
                $includeToArray = true;
                foreach ($classifications as $classification) {
                    $classificationType = $request->{'classification' . $classification->id_classification};
                    if ($request->{'filtro-classificacoes-caracteristica'} && $request->{'filtro-classificacoes-caracteristica'} == "on") {
                        if (($classificationType == 'type_a' && $classification->value <= 50)
                            || ($classificationType == 'type_b') && $classification->value >= 50
                        ) {
                            $includeToArray = false;
                            continue;
                        }
                    } else {
                        $classificationValue = $request->{'classification_detail' . $classification->id_classification};
                        $checkboxActive = $request->{'classification_detail_active' . $classification->id_classification};
                        $typeValue = $request->{'classification_detail' . $classification->id_classification . 'radio'};
                        if ($checkboxActive) {
                            if ($typeValue == 'type_a') {
                                if ($classification->value < $classificationValue) {
                                    $includeToArray = false;
                                    continue;
                                }
                            } else {
                                if ((100 - $classification->value) < $classificationValue) {
                                    $includeToArray = false;
                                    continue;
                                }
                            }
                        }
                    }
                }
                if ($includeToArray) {
                    $filteredByClassifications->push($discipline);
                }
            }
            $filteredDisciplines = $filteredByClassifications;
        }
        if ($request['check-filtro-aprovacao']) {
            $filteredByApproval = collect([]);
            foreach ($filteredDisciplines as $discipline) {
                $numStudents = 0;
                $numApprovedStudents = 0;
                $numFailedStudents = 0;
                $professorName = Professor::find($discipline->{'professor_id'})->name;
                $professorName = Normalizer::normalize($professorName, Normalizer::NFD);
                $professorName = preg_replace('/[\x{0300}-\x{036F}]/u', '', $professorName);
                $professorName = strtoupper($professorName);
                $performanceData = DisciplinePerformanceData::where('discipline_code', $discipline->code)->where('professors', 'like', '%' . $professorName . '%');
                if ($request->{'ano-aprov'} && $request->{'ano-aprov'} != "null") {
                    $performanceData->where('year', '=', $request->{'ano-aprov'});
                    if ($request->{'periodo-aprov'} && $request->{'periodo-aprov'} != "null") {
                        $performanceData->where('period', '=', $request->{'periodo-aprov'});
                    }
                }
                $performanceData = $performanceData->get();
                if (count($performanceData) == 0) {
                    continue;
                }
                foreach ($performanceData as $data) {
                    $numStudents += $data->{'num_students'};
                    $numApprovedStudents += $data->{'num_approved_students'};
                    $numFailedStudents += $data->{'num_failed_students'};
                }

                $approvedPercentage = ($numApprovedStudents / $numStudents) * 100;
                $failedPercentage = ($numFailedStudents / $numStudents) * 100;
                $valueComparation = $request->{'valor-comparacao'};
                if (($request->{'tipo-aprov'} == "aprov" && $request->{'comparacao'} == 'maior' && ($approvedPercentage > $valueComparation) ||
                    ($request->{'tipo-aprov'} == "aprov" && $request->{'comparacao'} == 'menor' && ($approvedPercentage < $valueComparation)) ||
                    ($request->{'tipo-aprov'} == "reprov" && $request->{'comparacao'} == 'maior' && ($failedPercentage > $valueComparation)) ||
                    ($request->{'tipo-aprov'} == "reprov" && $request->{'comparacao'} == 'menor' && ($failedPercentage < $valueComparation))
                )) {
                    $filteredByApproval->push($discipline);
                }
            }

            $filteredDisciplines = $filteredByApproval;
        }
        return $filteredDisciplines;
    }
}
