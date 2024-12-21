<?php

namespace Database\Seeders;

use App\Models\CourseLevel;
use App\Enums\CourseLevelValue;
use Illuminate\Database\Seeder;

class CourseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseLevel::create([
            'value' => CourseLevelValue::GRADUATION,
            'priority_level' => 10
        ]);
    }
}
