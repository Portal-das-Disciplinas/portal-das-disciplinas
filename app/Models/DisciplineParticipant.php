<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Esta classe representa o participante que produziu conteúdos para a página da disciplina.
 */
class DisciplineParticipant extends Model
{
    use HasFactory;

    /**
     * Atributos que são atribuíveis em massa.\n
     * name: Nome do participante.\n
     * role: Função do participante na produção de conteúdo da página.\n
     * email: e-mail do participante.\n
     * discipline_id: Referencia do id da disciplina na tabela disciplines.
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'discipline_id'
    ];

    /**
     * Retorna a disciplina da qual o participante produziu conteudo.
     * @return BelongsTo
     */
    function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Retorna os links das redes sociais do participante
     * @return HasMany
     */
    function links(){
       return $this->hasMany(ParticipantLink::class); 
    }
}
