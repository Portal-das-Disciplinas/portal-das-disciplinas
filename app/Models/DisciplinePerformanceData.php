<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinePerformanceData extends Model
{
    use HasFactory;
    protected $table = 'discipline_performance_datas';

    protected $fillable = [
        'discipline_code',
        'scheduling_update_id',
        'average_grade',
        'professors',
        'num_students',
        'num_approved_students',
        'num_failed_students',
        'class_code',
        'schedule_description',
        'year',
        'period',
        'highest_grade',
        'lowest_grade',
        'sum_grades',
        'last_update'
    ];

    public function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    public function schedulingDisciplineDataUpdate(){

        return $this->belongsTo(SchedulingDisciplineDataUpdate::class);
    }
}
