<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => RoleName::ADMIN,
                'priority_level' => 999,
            ],
            [
                'name' => RoleName::STUDENT,
                'priority_level' => 1,
            ],
            [
                'name' => RoleName::PROFESSOR,
                'priority_level' => 2,
            ],
        ];

        foreach ($roles as $role) {
            Role::query()
                ->updateOrCreate([
                    'name' => $role['name'],
                ], [
                    'priority_level' => $role['priority_level'],
                ]);
        }
    }
}
