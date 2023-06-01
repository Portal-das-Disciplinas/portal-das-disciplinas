<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationDiscipline extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo.
     *
     * @var string
     */
    protected $table = 'classifications_disciplines';
    /**
     * Nome da chave estrageira na tabela no banco de dados.
     */
    protected $primaryKey = 'classification_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'classification_id',
        'discipline_id',
        'value',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
