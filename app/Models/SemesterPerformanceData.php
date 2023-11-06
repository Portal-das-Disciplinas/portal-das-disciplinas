<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterPerformanceData extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'period',
        'data_amount',
        'last_data_created_at',
    ];


    function disciplinePerformanceData(){

        return $this->hasMany(DisciplinePerformanceData::class);
    }
}
