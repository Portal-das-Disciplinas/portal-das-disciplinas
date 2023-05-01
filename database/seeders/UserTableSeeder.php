<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Professor;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Students
         */
        $roleAdmin = Role::query()
            ->firstWhere('name', RoleName::ADMIN);

        // User::create([
        //     'name' => 'Victor',
        //     'email' => 'victor_brandao@outlook.com',
        //     'password' => '$2y$10$toyM29iTlElj.ShFuHu0KumYyhKXtSGXVhBHy.YzS9eSEg89sKQMW',
        //     'role_id' => $roleAdmin->id,
        // ]);

        // User::create([
        //     'name' => 'Pedro Gabriel',
        //     'email' => 'pedrogab96@gmail.com',
        //     'password' => '$2y$10$NHww673REnFjun6oaaChXub6MkPN.7WR//MNoUzZjh5ruqQTm7hIC',
        //     'role_id' => $roleAdmin->id,
        // ]);

        // User::create([
        //     'name' => 'Arthur Sérvulo',
        //     'email' => 'arthurservulo7@gmail.com',
        //     'password' => '$2y$10$rBccdnryA5R5GbYzzen82uR2Sz7Iu7BB6Vi18ocIypjyP2wPn9PY2',
        //     'role_id' => $roleAdmin->id,
        // ]);

        // User::create([
        //     'name' => 'Eugenio Paccelli',
        //     'email' => 'eugenio@admin.com',
        //     'password' => '$2y$10$LwYZ050GVQXu.gksBsEXyubkImKd6xLyhaiZxl0PeeuOcaOeq/7/K',
        //     'role_id' => $roleAdmin->id,
        // ]);

        // User::create([
        //     'name' => 'Cristian',
        //     'email' => 'cristian.filho@hotmail.com',
        //     'password' => '$2a$12$0JxVi.rm9L76ywKAWEJYxeaO7UZCjPJRX2QPJCrurqv8rW1zfIFny',
        //     'role_id' => $roleAdmin->id,
        // ]);

        // User::create([
        //     'name' => 'Henry',
        //     'email' => 'henrymedeiros77@gmail.com',
        //     'password' => '$2a$12$0s4fZEvN4mTK9N0Izny5h.wpb3UKVTI4kSgVNGwbY2O.1TA9s6Fg6',
        //     'role_id' => $roleAdmin->id,
        // ]);

            // User::create([
            //     'name' => 'admin',
            //     'email' => 'admin@gmail.com',
            //     'password' => bcrypt('adminportal@123'),
            //     'role_id' => $roleAdmin->id,
            // ]);

            // User::create([
            //     'name' => 'Henry',
            //     'email' => 'henrymedeiros77@gmail.com',
            //     'password' => bcrypt('adminportal@123'),
            //     'role_id' => $roleAdmin->id,
            // ]);

             User::create([
                 'name' => 'Eugenio',
                 'email' => 'eugenio@admin',
                 'password' => bcrypt('adminportal@123'),
                 'role_id' => $roleAdmin->id,
             ]);


        // if (app()->isLocal()) {
        //     User::create([
        //         'name' => 'Álvaro',
        //         'email' => 'alvarofepipa@gmail.com',
        //         'password' => bcrypt('mudar@123'),
        //         'role_id' => $roleAdmin->id,
        //     ]);

        //     User::create([
        //         'name' => 'test',
        //         'email' => 'test@gmail.com',
        //         'password' => 'senha123',
        //         'role_id' => $roleAdmin->id,
        //     ]);
        // }

        /*
         * Professors
         */
        // $roleProfessor = Role::query()
        //     ->firstWhere('name', RoleName::PROFESSOR);
        // $user = User::create([
        //     'name' => 'Eugenio Paccelli',
        //     'email' => 'eugenio@imd.ufrn.br',
        //     'password' => '$2y$10$LwYZ050GVQXu.gksBsEXyubkImKd6xLyhaiZxl0PeeuOcaOeq/7/K',
        //     'role_id' => $roleProfessor->id,
        // ]);
        // Professor::create([
        //     'name' => $user->name,
        //     'public_email' => $user->email,
        //     'profile_pic_link' => '',
        //     'user_id' => $user->id,
        // ]);

        // if (app()->isLocal()) {
        //     $user = User::create([
        //         'name' => 'Professor Tester',
        //         'email' => 'professor@gmail.com',
        //         'password' => bcrypt('mudar@123'),
        //         'role_id' => $roleProfessor->id,
        //     ]);
        //     Professor::create([
        //         'name' => $user->name,
        //         'public_email' => $user->email,
        //         'profile_pic_link' => '',
        //         'user_id' => $user->id,
        //     ]);
        // }
    }
}
