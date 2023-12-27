<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe que guarda o nome de uma classificação para a disciplina e o nome dos seus dois valores opostos.w
 * 
 */
class Classification extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo
     */
    protected $table = 'classifications';

    /**
     *Os atributos que são atribuíveis em massa, no metodo Classification::create.\n
     *name: Nome da classificação
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Retorna as disciplinas que possuem esta classificação(Classification).
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function disciplines()
    {
        return $this->hasManyThrough(Discipline::class, ClassificationDiscipline::class,
            'classification_id', 'id',
            'id', 'discipline_id');
    }

    /**
     * 
     * @param $discipline_id
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classificationDiscipline($discipline_id)
    {
        return $this->hasMany(ClassificationDiscipline::class, "classification_id", "id")
            ->where('discipline_id', $discipline_id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classificationsDisciplines()
    {
        return $this->hasMany(ClassificationDiscipline::class);
    }
}
