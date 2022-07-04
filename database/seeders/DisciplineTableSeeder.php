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
            'code' => 'IMD0029',
            'name' => 'Estrutura de dados básicas I',
            'description'=>'Nessa disciplina estudaremos como estruturar dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'professor_id' => 1
        ]);

        Discipline::create([
            'code' => 'IMD0030',
            'name' => 'Estrutura de dados básicas II',
            'description'=>'Nessa disciplina iremos continuar o estudo sobre estrutura de dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'professor_id' => 1
        ]);

        Discipline::create([
            'code' => 'IMD0029',
            'name' => 'Estrutura de dados básicas I',
            'description'=>'Iremos aprender como funciona a estrutura de dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'professor_id' => 1
        ]);

        Discipline::create([
            'code' => 'IMD0031',
            'name' => 'Vetores e Geometria Analítica',
            'description'=>'Iremos aprender funções vetoriais e geometria',
            'difficulties' => 'As dificuldades que os alunos encontram são ter um conhecimento básico em vetores e geometria.',
            'professor_id' => 1
        ]);

    }
}
