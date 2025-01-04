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
            'value' => EducationLevelValue::GRADUATION,
            'priority_level' => 10
        ]);
    }
}
