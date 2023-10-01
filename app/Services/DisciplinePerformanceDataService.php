<?php

namespace App\Services;

use App\Models\Discipline;
use App\Models\DisciplinePerformanceData;
use App\Models\SchedulingDisciplinePerfomanceDataUpdate;
use App\Services\APISigaa\APISigaaService;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Throwable;

class DisciplinePerformanceDataService
{


    public function save($data)
    {

        $schedule = new SchedulingDisciplinePerfomanceDataUpdate();
        $schedule->status = 'PENDING';
        $schedule->year = $data['year'];
        $schedule->period = $data['period'];
        if (isset($data['updateIfExists']) && $data['updateIfExists'] == 'on') {
            $schedule->{'update_if_exists'} = true;
        }
        $schedule->save();
    }

    function listAll()
    {

        return SchedulingDisciplinePerfomanceDataUpdate::all();
    }

    function delete($id)
    {
        SchedulingDisciplinePerfomanceDataUpdate::where('id', $id)->delete();
    }

    function updateDisciplinePerfomanceData()
    {
        return "Não implementado ainda";
    }

    function runSchedule($idSchedule)
    {

        $schedule = SchedulingDisciplinePerfomanceDataUpdate::find($idSchedule);
        $updateIfExists = $schedule->{'update_if_exists'};
        $apiService = new APISigaaService();
        $initialTime = microtime(true);
        $schedule->status = 'RUNNING';
        $schedule->save();
        $schedule->{'executed_at'} = date('Y-m-d h:i:s');
        $disciplineCodes = [];
        $disciplines = Discipline::all();

        foreach ($disciplines as $discipline) {
            if (in_array($discipline->code, $disciplineCodes) == false) {
                array_push($disciplineCodes, $discipline->code);
                $turmas = $apiService->getTurmasPorComponente($discipline->code, $schedule->year, $schedule->period);
                foreach ($turmas as $turma) {
                    $dataFromDatabase = DisciplinePerformanceData::where('discipline_code', '=', $discipline->code)->where('class_code', $turma['codigo-turma'])->where('year', '=', $schedule->year)->where('period', '=', $schedule->period)->first();
                    if ($dataFromDatabase == null) {
                        $apiPerfomanceClassData = $apiService->getDisciplineData($discipline->code, $turma['id-turma'], $schedule->year, $schedule->period);
                        $averageGrade = $apiPerfomanceClassData['soma-medias'] / $apiPerfomanceClassData['quantidade-discentes'];
                        DB::beginTransaction();
                        try {
                            $newData = DisciplinePerformanceData::create([
                                'discipline_code' => $discipline->code,
                                'scheduling_update_id' => $schedule->id,
                                'class_code' => $turma['codigo-turma'],
                                'schedule_description' => $turma['descricao-horario'],
                                'sum_grades' => $apiPerfomanceClassData['soma-medias'],
                                'average_grade' =>  $averageGrade,
                                'highest_grade' => $apiPerfomanceClassData['maior-media'],
                                'lowest_grade' => $apiPerfomanceClassData['menor-media'],
                                'num_students' => $apiPerfomanceClassData['quantidade-discentes'],
                                'num_approved_students' => $apiPerfomanceClassData['quantidade-aprovados'],
                                'num_failed_students' => $apiPerfomanceClassData['quantidade-reprovados'],
                                'year' => $turma['ano'],
                                'period' => $turma['periodo'],
                                'professors' => $apiPerfomanceClassData['docentes']
                            ]);
                            DB::commit();
                            $schedule->{'num_new_data'}++;
                        } catch (Exception $e) {
                            DB::rollBack();
                            $schedule->status = "ERROR";
                            $schedule->{'error_description'} = "Erro ao criar dados de desempenho de turma";
                        }
                    } else if ($dataFromDatabase != null && $updateIfExists) {
                        $apiPerfomanceClassData = $apiService->getDisciplineData($discipline->code, $turma['id-turma'], $schedule->year, $schedule->period);
                        $averageGrade = $apiPerfomanceClassData['soma-medias'] / $apiPerfomanceClassData['quantidade-discentes'];
                        DB::beginTransaction();
                        try {
                            $dataFromDatabase->{'discipline_code'} = $discipline->code;
                            $dataFromDatabase->{'scheduling_update_id'} = $schedule->id;
                            $dataFromDatabase->{'class_code'} = $turma['codigo-turma'];
                            $dataFromDatabase->{'schedule_description'} = $turma['descricao-horario'];
                            $dataFromDatabase->{'sum_grades'} = $apiPerfomanceClassData['soma-medias'];
                            $dataFromDatabase->{'average_grade'} = $averageGrade;
                            $dataFromDatabase->{'highest_grade'} = $apiPerfomanceClassData['maior-media'];
                            $dataFromDatabase->{'lowest_grade'} = $apiPerfomanceClassData['menor-media'];
                            $dataFromDatabase->{'num_students'} = $apiPerfomanceClassData['quantidade-discentes'];
                            $dataFromDatabase->{'num_approved_students'} = $apiPerfomanceClassData['quantidade-aprovados'];
                            $dataFromDatabase->{'num_failed_students'} = $apiPerfomanceClassData['quantidade-reprovados'];
                            $dataFromDatabase->{'year'} = $turma['ano'];
                            $dataFromDatabase->{'period'} = $turma['periodo'];
                            $dataFromDatabase->{'professors'} = $apiPerfomanceClassData['docentes'];
                            $dataFromDatabase->save();
                            DB::commit();
                            $schedule->{'num_updated_data'}++;
                        } catch (Exception $e) {
                            DB::rollBack();
                            $schedule->status = "ERROR";
                            $schedule->{'error_description'} = "Erro ao atualizar dados de desempenho de turma";
                        }
                    }
                }
            }
        }
        $schedule->{'update_time'} = microtime(true) - $initialTime;
        if ($schedule->status != "ERROR") {
            $schedule->status = "COMPLETE";
            $schedule->{'error_description'} = null;
        }
        $schedule->save();
    }

    function getPerformanceData($disciplineCode, $year, $period)
    {
        $datas = DisciplinePerformanceData::where('discipline_code', '=', $disciplineCode)->where('year', '=', $year)->where('period', '=', $period)->get();

        return $datas;
    }
}
