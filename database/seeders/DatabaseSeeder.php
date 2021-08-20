<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Discipline;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(ClassificationsSeeder::class);

        if (app()->isLocal()) {
            // $this->call(DisciplineTableSeeder::class);
            // User
            $users = User::factory()
                ->count(3)
                ->withRole(RoleName::PROFESSOR)
                ->create();
            // Professor
            $professors = $users->map(function (User $user) {
                return Professor::factory()
                    ->withUser($user)
                    ->create();
            });
            // Discipline
            $disciplines = $professors->map(function (Professor $professor) {
                return Discipline::factory()
                    ->withProfessor($professor)
                    ->createMediaAfter(2)
                    ->create();
            });
        }
    }
}
