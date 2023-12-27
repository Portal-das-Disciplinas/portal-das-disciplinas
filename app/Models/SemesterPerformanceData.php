<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Guarda as informações dos dados de desempenho de turmas de um semestre
 */
class SemesterPerformanceData extends Model
{
    use HasFactory;

    /**
     * Array com os atributos que são atribuíveis em massa pelo método SemesterPerformanceData::create.\n
     * year: Ano do semestre.\n
     * period: Período do semestre.\n
     * data_amount: Quantidade de dados de desempenho de turmas do semestre.\n
     * last_data_created_at: Data do último dado de desempenho de turma criado.\n
     */
    protected $fillable = [
        'year',
        'period',
        'data_amount',
        'last_data_created_at',
    ];


    /**
     * Retorna todos os dados de desempenho de turmas obtidos
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    function disciplinePerformanceData(){

        return $this->hasMany(DisciplinePerformanceData::class);
    }
}
