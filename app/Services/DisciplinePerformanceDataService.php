<?php

namespace App\Services;

use App\Exceptions\APISistemasIncorrectRequestExceception;
use App\Exceptions\APISistemasRequestLimitException;
use App\Exceptions\APISistemasServerErrorException;
use App\Exceptions\APISistemasUnavailableException;
use App\Models\Discipline;
use App\Models\DisciplinePerformanceData;
use App\Models\SchedulingDisciplinePerfomanceDataUpdate;
use App\Models\SemesterPerformanceData;
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

    function listSchedules($status, $paginate=null)
    {
        if(isset($paginate)){
            return  SchedulingDisciplinePerfomanceDataUpdate::where('status', '=', $status)->paginate($paginate);
        }
        return SchedulingDisciplinePerfomanceDataUpdate::where('status', '=', $status)->get();
    }

    function listAllSchedules()
    {

        return SchedulingDisciplinePerfomanceDataUpdate::all();
    }

    public function delete($id)
    {
        SchedulingDisciplinePerfomanceDataUpdate::where('id', $id)->delete();
    }

    private function updateSemesterPerformanceDataOnError($year, $period)
    {
        $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $year)->where('period', '=', $period)->first();
        if ($semesterPerformanceData) {
            $semesterPerformanceData->{'has_errors'} = true;
            $dataAmount = DisciplinePerformanceData::where('year', '=', $year)->where('period', '=', $period)->count();
            if ($dataAmount != $semesterPerformanceData->{'data_amount'}) {
                $semesterPerformanceData->{'data_amount'} = $dataAmount;
                $semesterPerformanceData->{'last_data_created_at'} = date('Y-m-d H:i:s');
            }
            $semesterPerformanceData->save();
        }
    }

    public function runSchedules()
    {
        $schedules = SchedulingDisciplinePerfomanceDataUpdate::where('status', '=', 'PENDING')->get();
        foreach ($schedules as $key => $schedule) {
            try {
                $this->runSchedule($schedule->id);
            } catch (APISistemasRequestLimitException $e1) {
                Log::error("Limite de requisições à API alcançado.");
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $schedule->year)->where('period', '=', $schedule->period)->first();
                $semesterPerformanceData->{'data_researched_at'} = date('Y-m-d H:i:s');
                $semesterPerformanceData->{'data_search_complete'} = true;
                $semesterPerformanceData->save();
                $s->status = "ERROR";
                $s->{'error_description'} .= " Limite de requisições alcançado";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s', $s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                $this->updateSemesterPerformanceDataOnError($s->year, $s->period);
                break;
            } catch (APISistemasServerErrorException $e2) {
                Log::error("Erro no servidor da API Sistemas.");
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $schedule->year)->where('period', '=', $schedule->period)->first();
                $semesterPerformanceData->{'data_researched_at'} = date('Y-m-d H:i:s');
                $semesterPerformanceData->{'data_search_complete'} = true;
                $semesterPerformanceData->save();
                $s->status = "ERROR";
                $s->{'error_description'} .= " Erro no servidor da API Sistemas";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s', $s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                $this->updateSemesterPerformanceDataOnError($s->year, $s->period);
                break;
            } catch (APISistemasIncorrectRequestExceception $e3) {
                Log::error("Erro nos parâmetros de requisição para a API.");
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $schedule->year)->where('period', '=', $schedule->period)->first();
                $semesterPerformanceData->{'data_researched_at'} = date('Y-m-d H:i:s');
                $semesterPerformanceData->{'data_search_complete'} = true;
                $semesterPerformanceData->save();
                $s->status = "ERROR";
                $s->{'error_description'} .= " Erro nos parâmetros de requisição para a API";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s', $s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                $this->updateSemesterPerformanceDataOnError($s->year, $s->period);
                break;
            } catch (APISistemasUnavailableException $e4) {
                Log::error("Não foi possível acessar a API Sistemas.");
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $schedule->year)->where('period', '=', $schedule->period)->first();
                $semesterPerformanceData->{'data_researched_at'} = date('Y-m-d H:i:s');
                $semesterPerformanceData->{'data_search_complete'} = true;
                $semesterPerformanceData->save();
                $s->status = "ERROR";
                $s->{'error_description'} .= " Não foi possível acessar a API";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s', $s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                $this->updateSemesterPerformanceDataOnError($s->year, $s->period);
                break;
            } catch (Exception $e5) {
                Log::error("Erro desconhecido ao executar a busca de dados da API: " . $e5->getMessage() + " " + $e5);
                $s = SchedulingDisciplinePerfomanceDataUpdate::find($schedule->id);
                $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $schedule->year)->where('period', '=', $schedule->period)->first();
                $semesterPerformanceData->{'data_researched_at'} = date('Y-m-d H:i:s');
                $semesterPerformanceData->{'data_search_complete'} = true;
                $semesterPerformanceData->save();
                $s->status = "ERROR";
                $s->{'error_description'} .= " Erro desconhecido";
                $diff = date_diff(DateTime::createFromFormat('Y-m-d H:i:s', $s->{'executed_at'}), date_create('now'));
                $s->{'update_time'} = $diff->h * 3600 + $diff->i * 60 + $diff->s;
                $schedule->{'finished_at'} = date('Y-m-d H:i:s');
                $s->save();
                $this->updateSemesterPerformanceDataOnError($s->year, $s->period);
                break;
            }
        }
    }

    function runSchedule($idSchedule)
    {
        Log::info('Executando o schedule: ' . $idSchedule);
        $schedule = SchedulingDisciplinePerfomanceDataUpdate::find($idSchedule);
        $updateIfExists = $schedule->{'update_if_exists'};
        $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $schedule->year)
            ->where('period', '=', $schedule->period)->first();
        if (!$semesterPerformanceData) {
            SemesterPerformanceData::create([
                'year' => $schedule->year,
                'period' => $schedule->period,
            ]);
        }
        $semesterPerformanceData = SemesterPerformanceData::where('year', '=', $schedule->year)
            ->where('period', '=', $schedule->period)->first();
        $semesterPerformanceData->{'data_search_complete'} = false;
        $semesterPerformanceData->save();
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
                                'discipline_name' => $turma['nome-componente'],
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
                                'professors' => $apiPerfomanceClassData['docentes'],
                                'semester_performance_id' => $semesterPerformanceData->id
                            ]);
                            DB::commit();
                            $semesterPerformanceData->{'last_data_created_at'} = date('Y-m-d H:i:s');
                            $semesterPerformanceData->{'data_amount'}++;
                            $schedule->{'num_new_data'}++;
                        } catch (Exception $e) {
                            Log::error("Erro ao criar dados de desempenho de turma: " . $turma['codigo-turma'] . " " . $e->getMessage());
                            DB::rollBack();
                            $semesterPerformanceData->{'has_errors'} = true;
                            $schedule->status = "ERROR";
                            $schedule->{'error_description'} = "Erro ao criar dados de desempenho de turma: " . $turma['codigo-turma'];
                        }
                    } else if ($dataFromDatabase != null && $updateIfExists) {
                        $apiPerfomanceClassData = $apiService->getDisciplineData($discipline->code, $turma['id-turma'], $schedule->year, $schedule->period);
                        $averageGrade = $apiPerfomanceClassData['soma-medias'] / $apiPerfomanceClassData['quantidade-discentes'];
                        DB::beginTransaction();
                        try {
                            $dataFromDatabase->{'discipline_code'} = $discipline->code;
                            $dataFromDatabase->{'discipline_name'} = $turma['nome-componente'];
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
                            $dataFromDatabase->{'semester_performance_id'} = $semesterPerformanceData->id;
                            $dataFromDatabase->save();
                            DB::commit();
                            $schedule->{'num_updated_data'}++;
                        } catch (Exception $e) {
                            Log::error("Erro ao atualizar dados de desempenho de turma: " . $turma['codigo-turma'] . " " . $e->getMessage());
                            DB::rollBack();
                            $semesterPerformanceData->{'has_errors'} = true;
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
            $semesterPerformanceData->{'has_errors'} = false;
        }
        $semesterPerformanceData->{'data_researched_at'} = date('Y-m-d H:i:s');
        $semesterPerformanceData->{'data_search_complete'} = true;

        $semesterPerformanceData->save();
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
    function getPerformanceData($disciplineCode, $year, $period, $paginate=null)
    {
        $data = DisciplinePerformanceData::query();

        if (isset($disciplineCode)) {
            $data = $data->where('discipline_code', '=', $disciplineCode);
        }
        if (isset($year)) {
            $data = $data->where('year', '=', $year);
        }
        if (isset($period)) {
            $data = $data->where('period', '=', $period);
        }
        if(isset($paginate)){
            return $data->paginate($paginate);
        }
        return $data->get();
    }
    
    function getPerformanceDataByInterval($disciplineCode, $yearStart, $periodStart, $yearEnd, $periodEnd,$paginate=null){

        Log::info($disciplineCode . " " . $yearStart . " " . $periodStart . " " . $yearEnd . " " . $periodEnd);
        if($yearStart == $yearEnd){

            $data = DisciplinePerformanceData::where('discipline_code','=',$disciplineCode)->where('year','=',$yearStart)->where('period','>=',$periodStart)->where('period','<=',$periodEnd);
            if(isset($paginate)){
                return $data->paginate($paginate);
            }
            return $data->get();
        }
        else{
            $data1 = DisciplinePerformanceData::where('discipline_code','=',$disciplineCode)->where('year','>=',$yearStart)->where('period','>=',$periodStart)->where('year','<=',$yearEnd - 1)->get();
            $data2 = DisciplinePerformanceData::where('discipline_code','=',$disciplineCode)->where('year','=',$yearEnd)->where('period','<=',$periodEnd)->get();
            $data = $data1->merge($data2)->all();
            if(isset($paginate)){
                return $data->paginate($paginate);
            }
            return $data;
        }

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
