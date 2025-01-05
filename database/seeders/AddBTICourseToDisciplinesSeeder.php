<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Discipline;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddBTICourseToDisciplinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $course = Course::where('name', '=', 'Bacharelado em Tecnologia da Informação')->first();
            if (!isset($course)) {
                throw new ModelNotFoundException("Não foi possível encontrar o curso com o nome especificado");
            }
            $disciplines = Discipline::all();
            foreach ($disciplines as $discipline) {
                $discipline->courses()->attach($course->id);
                $discipline->save();
            }
            DB::commit();
            Log::info("seeder: AddBTICourseToDisciplinesSeeder executado!");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
}
