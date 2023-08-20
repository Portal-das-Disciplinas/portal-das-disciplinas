<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;


    protected $table = 'medias'; /*!< Nome da tabela que representa o modelo

    /**
     * Os atributos que são atribuíveis em massa.
     *title: Título da mídia.\n
     *type: Tipo da mídia.\n
     *url:\n
     *is_trailer: Identifica se a mídia é um trailer.\n
     *discipline_id: ID da disciplina da qual a mídia faz parte.\n
     *view_url:  
     */
    protected $fillable = [
        'title',
        'type',
        'url',
        'is_trailer',
        'discipline_id',
        'view_url',
    ];

    /**
     *Os atributos que são convertidos para outro tipo.
     */
    protected $casts = [
        'is_trailer' => 'boolean',
    ];

    /**
     * Retorna a disciplina da qual este este objeto pertence.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
