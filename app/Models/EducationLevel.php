<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'priority_level'
    ];

    public function courses(){
        return $this->hasMany(Course::class);
    }

    public function disciplines(){
        return $this->hasMany(Discipline::class);
    }


}
