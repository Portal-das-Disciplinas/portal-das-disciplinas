<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe que representa uma pergunta que é muito frequente sobre a disciplina.
 */
class Faq extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo.
     */
    protected $table = 'faqs';

    /**
     * Array com os atributos que são atribuíveis em massa pelo método Faq::create.
     * title: Título da pergunta.\n
     * content: Conteúdo da pergunta.\n
     * discipline_id: ID da disciplina da qual contém a pergunta.
     */
    protected $fillable = [
        'title',
        'content',
        'discipline_id',
    ];

    /**
     * Retorna a disciplina da qual essa pergunta faz parte.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
