<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institutional_unit_id',
        'education_level_id'
    ];

    public function institutionalUnit(){
        return $this->belongsTo(InstitutionalUnit::class);
    }

    public function disciplines(){
        return $this->belongsToMany(Discipline::class);
    }


    public function educationLevel(){
        return $this->belongsTo(EducationLevel::class);
    }

}