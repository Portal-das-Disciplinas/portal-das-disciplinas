<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ClassificationDiscipline extends Model
{
    use HasFactory;

    /**
     * Classe que associa uma classificação com uma disciplina
     */
    protected $table = 'classifications_disciplines';

    /**
     * Nome da chave estrageira na tabela no banco de dados.
     */
    protected $primaryKey = 'classification_id';

    /**
     * Array com os atributos que são associados em massa, no método ClassificationDiscipline::create.\n
     * classification_id: ID da classificação(Classification).
     * discipline_id: ID da disciplina.
     * value: Valor da classificação.
     *
     */
    protected $fillable = [
        'classification_id',
        'discipline_id',
        'value',
    ];

    /**
     * Retorna a classificação.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * Retorna a disciplina
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
