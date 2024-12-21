<?php

namespace Database\Seeders;

use App\Models\InstitutionalUnit;
use Illuminate\Database\Seeder;

class InstitutionalUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InstitutionalUnit::create([
            'acronym' => 'IMD',
            'name' => 'Instituto Metrópole Digital'
        ]);
    }
}
