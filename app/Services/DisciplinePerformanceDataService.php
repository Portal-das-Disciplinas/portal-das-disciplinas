<?php

namespace App\Services;

use App\Exceptions\APISistemasIncorrectRequestExceception;
use App\Exceptions\APISistemasRequestLimitException;
use App\Exceptions\APISistemasServerErrorException;
use App\Exceptions\APISistemasUnavailableException;
use App\Models\Discipline;
use App\Models\DisciplinePerformanceData;
use App\Models\SchedulingDisciplinePerfomanceDataUpdate;
use App\Services\APISigaa\APISigaaService;
use DateTime;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
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

    function listSchedules($status)
    {
        $data = SchedulingDisciplinePerfomanceDataUpdate::where('status', '=', $status)->get();
        return $data;
    }

    function listAllSchedules()
    {

        return SchedulingDisciplinePerfomanceDataUpdate::all();
    }

    public function delete($id)
    {
        SchedulingDisciplinePerfomanceDataUpdate::where('id', $id)->delete();
    }

    function runSchedules()
    {
        $schedules = SchedulingDisciplinePerfomanceDataUpdate::where('status', '=', 'PENDING')->get();
        foreach ($schedules as $key=>$schedule) {
            try{
            $this->runSchedule($schedule->id);
            }catch(APISistemasRequestLimitException $e1){
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $s->status = "ERROR";
                $s->{'error_description'} .= " Limite de requisições alcançado";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s',$s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                Log::error("Limite de requisições à API alcançado.");
                break;
            }catch(APISistemasServerErrorException $e2){
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $s->status = "ERROR";
                $s->{'error_description'} .= " Erro no servidor da API Sistemas";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s',$s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                Log::error("Erro no servidor da API Sistemas.");
                break;
            }catch(APISistemasIncorrectRequestExceception $e3){
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $s->status = "ERROR";
                $s->{'error_description'} .= " Erro nos parâmetros de requisição para a API";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s',$s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                Log::error("Erro nos parâmetros de requisição para a API.");
                break;
            }catch(APISistemasUnavailableException $e4){
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $s->status = "ERROR";
                $s->{'error_description'} .= " Não foi possível acessar a API";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s',$s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                Log::error("Não foi possível acessar a API Sistemas.");
                break;
            }catch(Exception $e5){
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $s->status = "ERROR";
                $s->{'error_description'} .= " Erro desconhecido";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s',$s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                Log::error("Erro desconhecido ao executar a busca de dados da API: " . $e5->getMessage());
                break;
            }
        }
    }

    function runSchedule($idSchedule)
    {
        Log::info('Executando o schedule: ' . $idSchedule);
        $schedule = SchedulingDisciplinePerfomanceDataUpdate::find($idSchedule);
        $updateIfExists = $schedule->{'update_if_exists'};
        $apiService = new APISigaaService();
        $initialTime = microtime(true);
        $schedule->status = 'RUNNING';
        $schedule->{'executed_at'} = date('Y-m-d H:i:s');
        $schedule->save();
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
                            $schedule->{'error_description'} = "Erro ao criar dados de desempenho de turma: " . $turma['codigo-turma'];
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
                            $schedule->{'error_description'} = "Erro ao atualizar dados de desempenho de turma: " . $turma['codigo-turma'];
                        }
                    }
                }
            }
        }
       

        if ($schedule->status != "ERROR") {
            $schedule->status = "COMPLETE";
            $schedule->{'error_description'} = null;
        }
        $schedule->{'finished_at'} = date('Y-m-d H:i:s');
        $schedule->{'update_time'} = microtime(true) - $initialTime;
        $schedule->save();
    }

    /**
     * Retorna uma lista de dados de performance de uma turma
     * @param string $disciplineCode Codigo da disciplina (opcional)
     * @param string $year ano do semestre
     * @param int $year período do semestre
     */
    function getPerformanceData($disciplineCode, $year, $period)
    {
        if ($disciplineCode != null && $disciplineCode != "") {
            $datas = DisciplinePerformanceData::where('discipline_code', '=', $disciplineCode)->where('year', '=', $year)->where('period', '=', $period)->get();
        } else {
            $datas = DisciplinePerformanceData::where('year', '=', $year)->where('period', '=', $period)->get();
        }


        return $datas;
    }


    function deletePerformanceData($id)
    {
        $performanceData = DisciplinePerformanceData::find($id);
        $schedule = SchedulingDisciplinePerfomanceDataUpdate::where('id', '=', $performanceData->{'scheduling_update_id'})->first();
        DB::beginTransaction();
        try {
            $schedule->{'num_new_data'}--;
            $schedule->save();
            $performanceData->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    function deletePerformanceDataByCodeYearPeriod($code, $year, $period)
    {
        $query = DisciplinePerformanceData::where('discipline_code', '=', $code)
            ->where('year', '=', $year)
            ->where('period', '=', $period);
        DB::beginTransaction();
        try {
            foreach ($query->get() as $data) {
                $schedule = SchedulingDisciplinePerfomanceDataUpdate::where('id', '=', $data->{'scheduling_update_id'})->first();
                $schedule->{'num_new_data'}--;
                $schedule->save();
                $data->delete();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
