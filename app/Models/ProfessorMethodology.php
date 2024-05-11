<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorMethodology extends Model
{
    use HasFactory;

    protected $table = "professor_methodologies";

    protected $fillable = [
        'methodology_use_description',
        'professor_id',
        'methodology_id',
        'discipline_code'
    ];

    public function professor(){
        return $this->belongsTo(Professor::class);
    }

    public function methodology(){
        return $this->belongsTo(Methodology::class);
    }

    public function disciplines(){
        return $this->belongsToMany(Discipline::class,'discipline_professor_methodology','prof_methodology_id','discipline_id');
    }
}
