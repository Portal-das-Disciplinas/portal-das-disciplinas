<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorMethodology extends Model
{
    use HasFactory;

    protected $table = "professor_methodologies";

    protected $fillable = [
        'description',
        'professor_id',
        'methodology_id'
    ];

    public function professor(){
        return $this->belongsTo(Professor::class);
    }

    public function methodology(){
        return $this->belongsTo(Methodology::class);
    }
}
