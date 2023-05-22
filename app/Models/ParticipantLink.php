<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'discipline_participant_id'
    ];

    public function disciplineParticipant(){

        return $this->belongsTo(DisciplineParticipant::class);
    }
}
