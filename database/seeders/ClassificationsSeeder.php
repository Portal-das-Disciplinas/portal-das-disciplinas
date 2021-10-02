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
        ]);

        Classification::create([
            'id' => ClassificationID::DISCUSSAO,
            'name' => 'Discussão',
        ]);

        Classification::create([
            'id' => ClassificationID::ABORDAGEM,
            'name' => 'Abordagem',
        ]);

        Classification::create([
            'id' => ClassificationID::AVALIACAO,
            'name' => 'Avaliação',
        ]);
    }
}
