<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'discipline_id'
    ];

    public function discipline(){
        return $this->belongsTo(Discipline::class);
    }
}
