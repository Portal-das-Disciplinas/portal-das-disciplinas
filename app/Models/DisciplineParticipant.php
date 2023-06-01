<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Esta classe representa o participante que produziu conteúdos para a página da disciplina
 */
class DisciplineParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'email',
        'discipline_id'
    ];

    /**
     * Retorna a disciplina que o participante produziu conteudo.
     * @return BelongsTo
     */
    function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Retorna os links de redes sociais do participante
     * @return HasMany
     */
    function links(){
       return $this->hasMany(ParticipantLink::class); 
    }
}
