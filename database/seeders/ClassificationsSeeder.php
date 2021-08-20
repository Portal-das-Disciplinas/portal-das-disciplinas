<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Seeder;

use App\Enums\ClassificationID;

class ClassificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Classification::create([
            'id' => ClassificationID::METODOLOGIAS_CLASSICAS,
            'name' => 'Metodologias Clássicas',
        ]);

        Classification::create([
            'id' => ClassificationID::METODOLOGIAS_ATIVAS,
            'name' => 'Metodologias Ativas',
        ]);

        Classification::create([
            'id' => ClassificationID::DISCUSSAO_SOCIAL,
            'name' => 'Discussão Social',
        ]);

        Classification::create([
            'id' => ClassificationID::DISCUSSAO_TECNICA,
            'name' => 'Discussão Técnica',
        ]);

        Classification::create([
            'id' => ClassificationID::ABORDAGEM_TEORICA,
            'name' => 'Abordagem Teórica',
        ]);

        Classification::create([
            'id' => ClassificationID::ABORDAGEM_PRATICA,
            'name' => 'Abordagem Prática',
        ]);

        Classification::create([
            'id' => ClassificationID::AVALIACAO_PROVAS,
            'name' => 'Avaliação por Provas',
        ]);

        Classification::create([
            'id' => ClassificationID::AVALIACAO_ATIVIDADES,
            'name' => 'Avaliação por Atividades',
        ]);

    }
}
