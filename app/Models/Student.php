<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa as  informações do aluno
 */
class Student extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao modelo.
     */
    protected $table = 'students';

    /**
     * Array com os atributos que são atribuíveis em massa pelo método Student::create.\n
     * name: Nome do aluno.\n
     * profile_pic_link: Link da foto de perfil do aluno.\n
     * user_id: ID do usuário(User) do qual está vinculado ao aluno(Student).
     */
    protected $fillable = [
        'name',
        'profile_pic_link',
        'user_id',
    ];

    /**
     * Retorna o usuário(User) vinculado a este aluno(Student).
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
