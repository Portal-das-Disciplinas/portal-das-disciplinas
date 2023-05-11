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
            'name' => 'sectionNameCurrentCollaborators',
            'value' => 'Colaboradores Atuais'
        ]);

        Information::create([
            'name' => 'sectionNameFormerCollaborators',
            'value' => 'Antigos Colaboradores'
        ]);
    }
}
