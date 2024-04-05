<?php


namespace App\Services;

use App\Models\Discipline;
use App\Models\DisciplinePerformanceData;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Normalizer;

class DisciplineService
{

    public function filterDisciplines(Request $request)
    {
        $filteredDisciplines = collect([]);
        $disciplines = Discipline::query();
        if($request->name_discipline){
            $disciplines->where('name', 'like', '%' . $request->name_discipline . '%');
        }
        if ($request->emphasis) {
            $disciplines->where('emphasis_id', $request->emphasis);
        }
        if ($request->professors && $request->professors != "null") {
            $disciplines->where('professor_id', $request->professors);
        }
        if ($request['check-filtro-aprovacao']) {
            
            $disciplinesDB = $disciplines->get();
            foreach ($disciplinesDB as $discipline) {
                $numStudents = 0;
                $numApprovedStudents = 0;
                $numFailedStudents = 0;
                $professorName = Professor::find($discipline->{'professor_id'})->name;
                $professorName = Normalizer::normalize($professorName, Normalizer::NFD);
                $professorName = preg_replace('/[\x{0300}-\x{036F}]/u', '', $professorName);
                $professorName = strtoupper($professorName);
                $performanceData = DisciplinePerformanceData::where('discipline_code', $discipline->code)->where('professors', 'like', '%' . $professorName . '%');
                if($request->{'ano-aprov'} && $request->{'year-aprov'} != "null"){
                    $performanceData->where('year','=',$request->{'ano-aprov'});
                    if($request->{'periodo-aprov'} && $request->{'periodo-aprov'} != "null"){
                        $performanceData->where('period','=',$request->{'periodo-aprov'});
                    }
                }
                $performanceData = $performanceData->get();
                if(count($performanceData) == 0){
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
                    $filteredDisciplines->push($discipline);
                    //Log::info('código: ' . $discipline->code . ' aprovados: ' . $approvedPercentage . '%' . ' reprovados: '.$failedPercentage . '%'.' '. ' professor: ' . $data->professors);
                }


                   
            }

        }else{
            $filteredDisciplines = $disciplines->get();
        }
        return $filteredDisciplines;
    }
}
