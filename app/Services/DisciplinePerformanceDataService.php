<?php

namespace App\Services;

use App\Models\Discipline;
use App\Models\SchedulingDisciplinePerfomanceDataUpdate;
use Illuminate\Console\Scheduling\Schedule;

class DisciplinePerformanceDataService{


    public function save($data){

        $schedule = new SchedulingDisciplinePerfomanceDataUpdate();
        $schedule->status = 'PENDING';
        $schedule->year = $data['year'];
        $schedule->period = $data['period'];
        $schedule->save();

    }

    function listAll(){

        return SchedulingDisciplinePerfomanceDataUpdate::all();
    }

    function delete($id){
        SchedulingDisciplinePerfomanceDataUpdate::where('id',$id)->delete();
    }

}
