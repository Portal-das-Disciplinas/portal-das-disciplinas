<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulingDisciplinePerfomanceDataUpdate extends Model
{
    use HasFactory;

    protected $table = 'scheduling_performance_data_updates';

    /**
     * Campos que podem ser atribuídos em massa.
     * Possíveis valores de status: PENDING, RUNNING, COMPLETE, HAS_ERRORS
     */
    protected $fillable = [
        'status',
        'executed_at',
        'update_time',
        'num_new_datas',
        'year',
        'period',
        'error_description'
    ];

    public function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    public function disciplinePerfomanceDatas(){
        return $this->hasMany(DisciplinePerfomanceData::class);
    }
}
