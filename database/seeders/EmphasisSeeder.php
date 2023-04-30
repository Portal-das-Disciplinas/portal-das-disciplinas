<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'name' => 'Teste1'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Teste2'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Teste3'
        ]);

        DB::table('emphasis')->insert([
            'name' => 'Teste4'
        ]);
        
        DB::table('emphasis')->insert([
            'name' => 'Teste5'
        ]);
    }
}
