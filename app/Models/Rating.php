<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada com o modelo.
     *
     * @var string
     */
    protected $table = 'rating';

    /**
     * Array com os atributos que são atribuíveis em massa pelo método Rating:create.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'rating_value',
        'discipline_id',
        'student_id',
    ];

    /**
     * Retorna a disciplina da qual este objeto pertence
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Retorna o aluno do qual este objeto pertence
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
