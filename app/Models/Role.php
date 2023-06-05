<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
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
