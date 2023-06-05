<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Contém informações do aluno
 */
class Student extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * Os atributos que podem ser atribuidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'profile_pic_link',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
