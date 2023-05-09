<?php

namespace Database\Seeders;

use App\Models\Information;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Information::create([
            'name' => 'sectionNameCurrentCollaborator',
            'value' => 'Colaboradores AtuaisX'
        ]);

        Information::create([
            'name' => 'sectionNameFormerCollaborator',
            'value' => 'Antigos ColaboradoresX'
        ]);
    }
}
