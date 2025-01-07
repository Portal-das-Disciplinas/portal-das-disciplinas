<?php

namespace Database\Seeders;

use App\Enums\EducationLevelValue;
use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EducationLevel::create([
            'value' => EducationLevelValue::INFANTIL,
            'priority_level' => 1
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::FUNDAMENTAL,
            'priority_level' => 2
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::MEDIO,
            'priority_level' => 3
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::TECNICO,
            'priority_level' => 4
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::INTEGRADO,
            'priority_level' => 5
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::FORMACAO_COMPLEMENTAR,
            'priority_level' => 6
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::GRADUACAO,
            'priority_level' => 7
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::LATO_SENSU_ESPECIALIZACAO,
            'priority_level' => 8
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::LATO_SENSU_RESIDENCIA,
            'priority_level' => 9
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::STRICTO_SENSU,
            'priority_level' => 10
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::MESTRADO,
            'priority_level' => 11
        ]);

        EducationLevel::create([
            'value' => EducationLevelValue::DOUTORADO,
            'priority_level' => 12
        ]);


    }
}
