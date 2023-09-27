<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinePerfomanceData extends Model
{
    use HasFactory;

    protected $fillable = [
        'discipline_id',
        'scheduling_update_id',
        'average_grade',
        'num_students',
        'num_approved_students',
        'num_failed_students',
        'class_code',
        'schedule_description',
        'year',
        'period',
        'sum_grades',
        'exists_class',
        'last_update'
    ];

    public function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    public function schedulingDisciplineDataUpdate(){

        return $this->belongsTo(SchedulingDisciplineDataUpdate::class);
    }
}
