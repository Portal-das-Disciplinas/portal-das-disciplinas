<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Esta classe representa a ênfase de uma disciplina
 */
class Emphasis extends Model
{
    use HasFactory;

    /**
     * Nome da tabela no banco de dados
     */
    protected $table = 'emphasis';
    
    /**
     * Array com os atributos que são atribuíveis em massa pelo método Emphasis::create.\n
     * name: Nome da ênfase.
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Retorna as disciplinas que possuem esta ênfase
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disciplines()
    {
        return $this->hasMany(Discipline::class);
    }
}
