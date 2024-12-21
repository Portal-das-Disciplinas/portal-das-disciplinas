<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function institutionalUnity(){
        return $this->belongsTo(InstitutionalUnit::class);
    }

    public function disciplines(){
        return $this->belongsToMany(Discipline::class);
    }


    public function courseLevel(){
        return $this->belongsTo(CourseLevel::class);
    }

}