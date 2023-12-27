<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe que representa um agendamento de busca de dados de desempenho de turmas das disciplinas na API Sistemas.
 */
class SchedulingDisciplinePerfomanceDataUpdate extends Model
{
    use HasFactory;

    protected $table = 'scheduling_performance_data_updates';

    /**
     * Array com os atributos que são atribuíveis em massa no método SchedulingDisciplinePerformanceDataUpdate::create.
     * status: Status do agendamento: PENDING, RUNNING, COMPLETE, HAS_ERRORS.\n
     * executed_at: Horário em que o agendamento foi executado.\n
     * update_time: Tempo da execução da atualização dos dados.\n
     * num_new_data: Quantidade de dados que foram criados na execução do agendamento.\n
     * year: Ano do semestre a ser pesquisado.\n
     * period: Período do semestre a ser pesquisado.\n
     * update_if_existis: Mesmo que um dado já exista no portal, é feita uma pesquisa na API Sistemas.\n
     * error_description: Guarda a informação do erro casa ocorra ao obter os dados no agendamento.\n
     */
    protected $fillable = [
        'status',
        'executed_at',
        'update_time',
        'num_new_data',
        'year',
        'period',
        'update_if_exists',
        'error_description'
    ];

    /**
     * Retorna todos os dados de desempenho de turmas obtidos
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disciplinePerfomanceDatas(){
        return $this->hasMany(DisciplinePerformanceData::class);
    }
}
