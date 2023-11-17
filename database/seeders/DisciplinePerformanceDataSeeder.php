<?php

namespace Database\Seeders;

use App\Models\DisciplinePerformanceData;
use App\Models\SemesterPerformanceData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use stdClass;

use function PHPSTORM_META\map;

/* Classe para Teste. Não incluir na função call da classe DatabaseSeeder */
class DisciplinePerformanceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DisciplinePerformanceData::query()->delete();
        SemesterPerformanceData::query()->delete();

        //Ano 2020
        $semester2020_1 = new SemesterPerformanceData();
        $semester2020_1->year = 2020;
        $semester2020_1->period=1;
        $semester2020_1->save();

        $semester2020_2 = new SemesterPerformanceData();
        $semester2020_2->year = 2020;
        $semester2020_2->period=2;
        $semester2020_2->save();

        $semester2020_3 = new SemesterPerformanceData();
        $semester2020_3->year = 2020;
        $semester2020_3->period=3;
        $semester2020_3->save();

        //Ano 2021
        $semester2021_1 = new SemesterPerformanceData();
        $semester2021_1->year = 2021;
        $semester2021_1->period=1;
        $semester2021_1->save();

        $semester2021_2 = new SemesterPerformanceData();
        $semester2021_2->year = 2021;
        $semester2021_2->period=2;
        $semester2021_2->save();

        $semester2021_3 = new SemesterPerformanceData();
        $semester2021_3->year = 2021;
        $semester2021_3->period=3;
        $semester2021_3->save();

        //Ano 2022
        $semester2022_1 = new SemesterPerformanceData();
        $semester2022_1->year = 2022;
        $semester2022_1->period=1;
        $semester2022_1->save();

        $semester2022_2 = new SemesterPerformanceData();
        $semester2022_2->year = 2022;
        $semester2022_2->period=2;
        $semester2022_2->save();

        $semester2022_3 = new SemesterPerformanceData();
        $semester2022_3->year = 2022;
        $semester2022_3->period=3;
        $semester2022_3->save();

        //Ano 2023
        $semester2023_1 = new SemesterPerformanceData();
        $semester2023_1->year = 2023;
        $semester2023_1->period=1;
        $semester2023_1->save();

        $semester2023_2 = new SemesterPerformanceData();
        $semester2023_2->year = 2023;
        $semester2023_2->period=2;
        $semester2023_2->save();

        $semester2023_3 = new SemesterPerformanceData();
        $semester2023_3->year = 2023;
        $semester2023_3->period=3;
        $semester2023_3->save();

        /*Disciplinas de matemática 2020.1*/
        $professor = new stdClass();
        $professor->nome = 'Joao';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 7.5,
            'num_students' => 10,
            'num_approved_students' => 5,
            'num_failed_students' => 3,
            'class_code' =>'01',
            'year' => 2020,
            'period' =>1,
            'sum_grades' => 80,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Maria';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 8.5,
            'num_students' => 10,
            'num_approved_students' => 5,
            'num_failed_students' => 3,
            'class_code' =>'02',
            'year' => 2020,
            'period' =>1,
            'sum_grades' => 80,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Marina';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 10,
            'num_students' => 10,
            'num_approved_students' => 10,
            'num_failed_students' => 0,
            'class_code' =>'03',
            'year' => 2020,
            'period' =>1,
            'sum_grades' => 100,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Fernando';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 9,
            'num_students' => 10,
            'num_approved_students' => 9,
            'num_failed_students' => 1,
            'class_code' =>'04',
            'year' => 2020,
            'period' =>1,
            'sum_grades' => 88,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_1->id
        ]);

        /*Disciplinas de matemática 2020.2*/
        $professor = new stdClass();
        $professor->nome = 'Julianna';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 7.5,
            'num_students' => 100,
            'num_approved_students' => 60,
            'num_failed_students' => 40,
            'class_code' =>'01',
            'year' => 2020,
            'period' =>2,
            'sum_grades' => 7000,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_2->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Marcos';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 8,
            'num_students' => 100,
            'num_approved_students' => 60,
            'num_failed_students' => 40,
            'class_code' =>'02',
            'year' => 2020,
            'period' =>2,
            'sum_grades' => 7000,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_2->id
        ]);

        /*Disciplina de Matemática 2020.3*/
        $professor = new stdClass();
        $professor->nome = 'Teresa';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 50,
            'num_approved_students' => 25,
            'num_failed_students' => 25,
            'class_code' =>'03',
            'year' => 2020,
            'period' =>3,
            'sum_grades' => 250,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_3->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Vania';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 60,
            'num_approved_students' => 40,
            'num_failed_students' => 20,
            'class_code' =>'04',
            'year' => 2020,
            'period' =>3,
            'sum_grades' => 300,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2020_3->id
        ]);

        /*Disciplinas de matemática no período 2021.1*/
        $professor = new stdClass();
        $professor->nome = 'Roberto';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 60,
            'num_approved_students' => 40,
            'num_failed_students' => 20,
            'class_code' =>'01',
            'year' => 2021,
            'period' =>1,
            'sum_grades' => 300,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2021_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Felipe';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 60,
            'num_approved_students' => 40,
            'num_failed_students' => 20,
            'class_code' =>'02',
            'year' => 2021,
            'period' =>1,
            'sum_grades' => 300,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2021_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Josevaldo';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 60,
            'num_approved_students' => 40,
            'num_failed_students' => 20,
            'class_code' =>'03',
            'year' => 2021,
            'period' =>1,
            'sum_grades' => 300,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2021_1->id
        ]);

        /*Disciplinas de matemática do semestre 2021.2*/
        $professor = new stdClass();
        $professor->nome = 'Joseana';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 60,
            'num_approved_students' => 40,
            'num_failed_students' => 20,
            'class_code' =>'01',
            'year' => 2021,
            'period' =>2,
            'sum_grades' => 300,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2021_2->id
        ]);

        /*Disciplinas de matemática do semestre 2021.3*/
        $professor = new stdClass();
        $professor->nome = 'Tatyana';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 8,
            'num_students' => 100,
            'num_approved_students' => 100,
            'num_failed_students' => 0,
            'class_code' =>'01',
            'year' => 2021,
            'period' =>3,
            'sum_grades' => 800,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2021_3->id
        ]);

        /*Disciplinas de matemática do semestre 2022.1*/
        $professor = new stdClass();
        $professor->nome = 'Francisco';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 8,
            'num_students' => 100,
            'num_approved_students' => 100,
            'num_failed_students' => 0,
            'class_code' =>'01',
            'year' => 2022,
            'period' =>1,
            'sum_grades' => 800,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2022_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Francisca';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 4.5,
            'num_students' => 100,
            'num_approved_students' => 40,
            'num_failed_students' => 60,
            'class_code' =>'02',
            'year' => 2022,
            'period' =>1,
            'sum_grades' => 450,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2022_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Marcelo';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 120,
            'num_approved_students' => 60,
            'num_failed_students' => 60,
            'class_code' =>'03',
            'year' => 2022,
            'period' =>1,
            'sum_grades' => 600,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2022_1->id
        ]);

        /*Disciplinas de matemática 2022.2 */
        $professor = new stdClass();
        $professor->nome = 'Gustavo';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 120,
            'num_approved_students' => 60,
            'num_failed_students' => 60,
            'class_code' =>'01',
            'year' => 2022,
            'period' =>2,
            'sum_grades' => 600,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2022_2->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Augusto';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 5,
            'num_students' => 120,
            'num_approved_students' => 60,
            'num_failed_students' => 60,
            'class_code' =>'02',
            'year' => 2022,
            'period' =>2,
            'sum_grades' => 600,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2022_2->id
        ]);

        /*Disciplinas de matemática 2023.1 */
        $professor = new stdClass();
        $professor->nome = 'Leonardo';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 6,
            'num_students' => 100,
            'num_approved_students' => 70,
            'num_failed_students' => 30,
            'class_code' =>'01',
            'year' => 2023,
            'period' =>1,
            'sum_grades' => 600,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2023_1->id
        ]);

        $professor = new stdClass();
        $professor->nome = 'Sergio';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 7,
            'num_students' => 100,
            'num_approved_students' => 72,
            'num_failed_students' => 28,
            'class_code' =>'02',
            'year' => 2023,
            'period' =>1,
            'sum_grades' => 650,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2023_1->id
        ]);

        /*Disciplinas de matemática 2023.2*/
        $professor = new stdClass();
        $professor->nome = 'Sergio';
        $professors = [];
        array_push($professors, json_encode([$professor]));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 6,
            'num_students' => 100,
            'num_approved_students' => 70,
            'num_failed_students' => 30,
            'class_code' =>'01',
            'year' => 2023,
            'period' =>2,
            'sum_grades' => 600,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2023_2->id
        ]);

        /*Disciplinas de matemática 2023.3 */
        $professor = new stdClass();
        $professor->nome = 'Carlos';
        $professors = [];
        
        array_push($professors, json_encode([$professor]));
        Log::info(json_encode($professors));
        DisciplinePerformanceData::create([
            'discipline_code' => 'MAT020',
            'discipline_name' => 'Matemática Básica',
            'professors' => json_encode($professors),
            'average_grade' => 6,
            'num_students' => 100,
            'num_approved_students' => 70,
            'num_failed_students' => 30,
            'class_code' =>'01',
            'year' => 2023,
            'period' =>3,
            'sum_grades' => 600,
            'schedule_description' =>'',
            'semester_performance_id' => $semester2023_3->id
        ]);

    }
}
