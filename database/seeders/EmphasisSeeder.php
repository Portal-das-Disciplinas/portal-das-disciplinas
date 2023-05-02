<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Emphasis;

class EmphasisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('emphasis')->insert([
            'name' => 'Núcleo Comum'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Computação'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Desenvolvimento de Software'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Informática Educacional'
        ]);
        
        DB::table('emphasis')->insert([
            'name' => 'Sistemas de Informação de Gestão'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Bioinformática'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Internet das Coisas'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Jogos Digitais'
        ]);
    }
}
