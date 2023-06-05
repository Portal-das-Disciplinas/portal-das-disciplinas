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

    protected $table = 'emphasis';
    
    /**
     * Os 
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
