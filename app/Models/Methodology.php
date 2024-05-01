<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Methodology extends Model
{
    use HasFactory;
    protected $table = "methodologies";

    public $fillable = [
        'name',
        'professor_id',
        'discipline_code',
        'description'
    ];

    public function createdByProfessor(){
        return $this->belongsTo(Professor::class);
    }
}
