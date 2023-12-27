<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Esta classe representa do nível de acesso de um usuário,
 * como administrador, professor, aluno.
 */
class Role extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo.
     */
    protected $table = 'roles';

    /**
     * Array com os atributos que são atribuíveis em massa pelo método Role::create.\n
     * name: Nome da Role.\n
     * priority_level: Nível de acesso.
     */
    protected $fillable = [
        'name',
        'priority_level',
    ];

    /**
     * Retorna todos os usuários que possuem esse objeto
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get priority_level attribute.
     *
     * @param  integer  $value
     * @return integer
     */
    public function getPriorityLevelAttribute($value)
    {
        return ucfirst($value);
    }
}
