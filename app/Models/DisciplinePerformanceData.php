<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe que guarda os dados de desempenho de uma turma, obtidos pela pesquisa na API Sistemas.
 */
class DisciplinePerformanceData extends Model
{
    use HasFactory;
    /**
     * Nome da tabela no banco de dados.
     */
    protected $table = 'discipline_performance_datas';

    /**
     * Array que guarda os nomes do atributos que são atribuíveis em massa pelo método DisciplinePerformanceData::create.\n
     * discipline_code: Código da disciplina.\n
     * discipline_name: Nome da disciplina.\n
     * average_grade: Nota média da turma.\n
     * professors: String no formato de array com o nome do professores da turma.\n
     * num_students: Quantidade de alunos da turma.\n
     * num_approved_students: Quantidade de alunos aprovados.\n
     * num_failed_students: Quantidade de alunos reprovados.\n
     * class_code: Código da turma.\n
     * schedule_description:\n
     * year: Ano da turma.\n
     * period: Período da turma.\n
     * highest_grade: Maior nota da turma.\n
     * lowest_grade: Menor nota da turma.\n
     * sum_grades: Somatório das notas dos discentes da turma.\n
     * last_update:Data da última atualização dos dados da turma.\n
     * semester_performance_id: Semestre em que este dados está associado.\n
     */
    protected $fillable = [
        'discipline_code',
        'discipline_name',
        'average_grade',
        'professors',
        'num_students',
        'num_approved_students',
        'num_failed_students',
        'class_code',
        'schedule_description',
        'year',
        'period',
        'highest_grade',
        'lowest_grade',
        'sum_grades',
        'last_update',
        'semester_performance_id'
    ];

    public function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    public function calculatePercentage () {
        $percentage = round(($this->num_approved_students*100) / $this->num_students);

        return $percentage;
    }
}
