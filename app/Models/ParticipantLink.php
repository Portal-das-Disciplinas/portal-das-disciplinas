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
     * Atributos do modelo no banco de dados para associação em massa no método create\n
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
