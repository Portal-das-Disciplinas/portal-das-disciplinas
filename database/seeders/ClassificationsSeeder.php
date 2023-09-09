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
            'id' => ClassificationID::METODOLOGIAS,
            'name' => 'Metodologias',
            'type_a' => 'Clássicas',
            'type_b' => 'Ativas',
            'type_a_no_accentuation' => 'classicas', 
            'type_b_no_accentuation' => 'ativas'
        ]);

        Classification::create([
            'id' => ClassificationID::DISCUSSAO,
            'name' => 'Discussão',
            'type_a' => 'Social',
            'type_b' => 'Técnica',
            'type_a_no_accentuation' => 'social',
            'type_b_no_accentuation' => 'tecnica'
        ]);

        Classification::create([
            'id' => ClassificationID::ABORDAGEM,
            'name' => 'Abordagem',
            'type_a' => 'Teórica',
            'type_b' => 'Prática',
            'type_a_no_accentuation' => 'teorica',
            'type_b_no_accentuation' => 'pratica'
        ]);

        Classification::create([
            'id' => ClassificationID::AVALIACAO,
            'name' => 'Avaliação',
            'type_a' => 'Provas',
            'type_b' => 'Atividades',
            'type_a_no_accentuation' => 'provas',
            'type_b_no_accentuation' => 'atividades'
        ]);
    }
}
