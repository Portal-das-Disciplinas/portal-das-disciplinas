<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Esta classe guarda o link do participante da produção de conteúdos da disciplina.
 */
class ParticipantLink extends Model
{
    use HasFactory;

    /**
     * Campos do modelo no banco de dados
     * @param name Nome do link da rede social do participante
     * @param url Endereço do link da rede social do participante
     * @param discipline_participant_id O ID do participante no banco de dados
     */
    protected $fillable = [
        'name',
        'url',
        'discipline_participant_id'
    ];

    /**
     * Retorna o partipante do qual é dono do link
     * @return DisciplineParticipant
     */
    public function disciplineParticipant(){

        return $this->belongsTo(DisciplineParticipant::class);
    }
}
