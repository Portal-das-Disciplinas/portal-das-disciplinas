<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Discipline;


class DisciplineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discipline::create([
            'emphasis_id' => 1,
            'professor_id' => 1,
            'code' => 'IMD0029',
            'name' => 'Estrutura de dados básicas I',
            'description'=>'Nessa disciplina estudaremos como estruturar dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 2,
            'professor_id' => 1,
            'code' => 'IMD0030',
            'name' => 'Estrutura de dados básicas II',
            'description'=>'Nessa disciplina iremos continuar o estudo sobre estrutura de dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 2,
            'professor_id' => 1,
            'code' => 'IMD0029',
            'name' => 'Estrutura de dados básicas III',
            'description'=>'Iremos aprender como funciona a estrutura de dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 4,
            'professor_id' => 1,
            'code' => 'IMD0031',
            'name' => 'Vetores e Geometria Analítica',
            'description'=>'Iremos aprender funções vetoriais e geometria',
            'difficulties' => 'As dificuldades que os alunos encontram são ter um conhecimento básico em vetores e geometria.',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 5,
            'professor_id' => 1,
            'code' => 'IMD0501',
            'name' => 'Fundamentos Pedagógicos para a Informática Educacional',
            'description'=>'Iremos aprender a fundamentação pedagógica...',
            'difficulties' => 'Uma das maiores dificuldades dos alunos é se adaptar ao modelo de ensino, que exige uma participação mais ativa dos discentes ao decorrer das aulas. Além disso, como é uma disciplina voltada às ciências humanas, a elaboração de respostas discursivas se torna uma das dificuldades enfrentadas por alguns alunos.',
            'acquirements' => 'Teste',
            
        ]);
    }
}
