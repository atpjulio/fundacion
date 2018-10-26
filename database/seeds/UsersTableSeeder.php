<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Initial users
        $initialUsers = [
            [
                'first_name' => 'Ingrid',
                'last_name' => 'Jiménez',
                'email' => 'ingrid@fundacion.com',
                'password' => bcrypt('123456'),
                'user_type' => config('constants.userRoles.admin'),
            ],
            [
                'first_name' => 'Zuleima',
                'last_name' => 'Osorio',
                'email' => 'zuleima0326@gmail.com',
                'password' => bcrypt('123456'),
                'user_type' => config('constants.userRoles.admin'),
            ],
            [
                'first_name' => 'Ivette',
                'last_name' => 'Devivo',
                'email' => 'ivettedevivo@hotmail.com',
                'password' => bcrypt('123456'),
                'user_type' => config('constants.userRoles.admin'),
            ],
            [
                'first_name' => 'Administrador',
                'last_name' => 'Barranquilla',
                'email' => 'elmilagrobq1@gmail.com',
                'password' => bcrypt('fundacion'),
                'user_type' => config('constants.userRoles.user'),
            ],
            [
                'first_name' => 'Julio',
                'last_name' => 'Amaya',
                'email' => 'atpjulio@yahoo.es',
                'password' => bcrypt('123456'),
                'user_type' => config('constants.userRoles.admin'),
            ],
        ];

        foreach ($initialUsers as $currentUser) {
            $user = User::create($currentUser);
            $user->assignRole(config('constants.userRolesString')[$currentUser['user_type']]);
        }
    }
}
