<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'email',
        'discipline_id'
    ];

    function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    function links(){
       return $this->hasMany(ParticipantLink::class); 
    }
}
