<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Esta classe guarda o link do participante que contribuiu na produção de conteúdos da disciplina.
 */
class ParticipantLink extends Model
{
    use HasFactory;

    /**
     * Array com os atributos que são associados em massa pelo método ParticipantLink::create.\n
     * name: Nome do link da rede social do participante\n
     * url: Endereço do link da rede social do participante\n
     * discipline_participant_id: O ID do participante no banco de dados\n
     */
    protected $fillable = [
        'name',
        'url',
        'discipline_participant_id'
    ];

    /**
     * Retorna o partipante do qual é dono do link
     * @return BelongsTo
     */
    public function disciplineParticipant(){

        return $this->belongsTo(DisciplineParticipant::class);
    }
}
