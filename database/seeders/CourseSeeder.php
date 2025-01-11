<?php

namespace Database\Seeders;

use App\Enums\CourseLevelValue;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\InstitutionalUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courseLevel = CourseLevel::where('value','=',CourseLevelValue::GRADUATION)->first();
        $institutionalUnit = InstitutionalUnit::where('acronym','=','IMD')->first();
        $BTICourse = new Course();
        $BTICourse->name = 'Bacharelado em Tecnologia da Informação';
        $BTICourse->{'institutional_unit_id'} = $institutionalUnit->id;
        $BTICourse->{'course_level_id'} = $courseLevel->id;
        $BTICourse->save();

    }
}
