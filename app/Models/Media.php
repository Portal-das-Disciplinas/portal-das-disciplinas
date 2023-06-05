<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo.
     *
     * @var string
     */
    protected $table = 'medias';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
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
     * The attributes that should be cast.
     *
     * @var array
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
