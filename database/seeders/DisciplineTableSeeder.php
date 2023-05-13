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
            'description' => 'Nessa disciplina estudaremos como estruturar dados.',
            'synopsis'=>'Nessa disciplina estudaremos como estruturar dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 2,
            'professor_id' => 1,
            'code' => 'IMD0030',
            'name' => 'Estrutura de dados básicas II',
            'description' => 'Nessa disciplina iremos continuar o estudo sobre estrutura de dados.',
            'synopsis'=>'Nessa disciplina iremos continuar o estudo sobre estrutura de dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 2,
            'professor_id' => 1,
            'code' => 'IMD0029',
            'name' => 'Estrutura de dados básicas III',
            'description' => 'Iremos aprender como funciona a estrutura de dados.',
            'synopsis'=>'Iremos aprender como funciona a estrutura de dados.',
            'difficulties' => 'As dificuldades que os alunos encontram são programar com c++',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 4,
            'professor_id' => 1,
            'code' => 'IMD0031',
            'name' => 'Vetores e Geometria Analítica',
            'description' => 'Iremos aprender funções vetoriais e geometria',
            'synopsis'=>'Iremos aprender funções vetoriais e geometria',
            'difficulties' => 'As dificuldades que os alunos encontram são ter um conhecimento básico em vetores e geometria.',
            'acquirements' => 'Progamação Orientada a objeto',
            
        ]);

        Discipline::create([
            'emphasis_id' => 5,
            'professor_id' => 1,
            'code' => 'IMD0501',
            'name' => 'Fundamentos Pedagógicos para a Informática Educacional',
            'description' => 'Iremos aprender a fundamentação pedagógica...',
            'synopsis'=>'Iremos aprender a fundamentação pedagógica...',
            'difficulties' => 'Uma das maiores dificuldades dos alunos é se adaptar ao modelo de ensino, que exige uma participação mais ativa dos discentes ao decorrer das aulas. Além disso, como é uma disciplina voltada às ciências humanas, a elaboração de respostas discursivas se torna uma das dificuldades enfrentadas por alguns alunos.',
            'acquirements' => 'Teste',
            
        ]);

        Discipline::create([
            'emphasis_id' => 2,
            'professor_id' => 1,
            'code' => 'IMD1303',
            'name' => 'Estrutura de estudos de Mercado',
            'description' => 'Chegou a hora de aprender sobre relacionamento com clientes, ambiente competitivo, análise mercadológica e desenvolver seus conhecimentos na área de estratégia empresarial e na área de marketing. Aprender a fazer análises dos ambientes internos e externos do local onde trabalha e como o mesmo se relaciona com o meio no qual está inserido. Topa esse desafio? No final você perceberá que é um desafio gratificante e que te proporcionará novos conhecimentos e novas habilidades.',
            'synopsis'=>'Chegou a hora de aprender sobre relacionamento com clientes, ambiente competitivo, análise mercadológica e desenvolver seus conhecimentos na área de estratégia empresarial e na área de marketing. Aprender a fazer análises dos ambientes internos e externos do local onde trabalha e como o mesmo se relaciona com o meio no qual está inserido. Topa esse desafio? No final você perceberá que é um desafio gratificante e que te proporcionará novos conhecimentos e novas habilidades.',
            'difficulties' => 'a maior dificuldade é vencer a timidez para se associar em grupos e então desenvolver a parte prática que é assim realizada. No mesmo sentido o aluno tem que interagir com prepostos de uma STARTUP e se tornar ativo na busca pela informação relativa a seus clientes, entrevistando os mesmos e formatando pesquisas. Vencido o desafio inicial de se incorporar a um grupo, o aluno, ainda que tímido, pode desenvolver atividades muito bem encaixadas em sua equipe, pois mesmo não sendo o membro que faz as entrevistas ele pode possuir insights, ter boas ideias e ser um ótimo relator / redator. Então, após formar o grupo, a complementação de competências entre os vários participantes será o determinante no sucesso do aprendizado de todos. Entender os objetivos e entender que é necessário ouvir o cliente para que ocorra uma aproximação dele com o produto, é uma competência que deve ser desenvolvida. Isso não seria um obstáculo, mas uma complexidade a ser vencida pelo desenvolvimento pessoal de todos. Entender como perceber a "dor" do cliente é fundamental e imprescindível para desenvolver os conteúdos. Trata-se de uma mudança de viés, do tecnicismo (por exemplo "o fazer programas") para a visão das necessidades reais do cliente, transformando-as num produto ou em um serviço.',
            'acquirements' => 'Teste',
            
        ]);
    }
}
