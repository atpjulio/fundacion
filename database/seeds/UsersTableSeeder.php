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
                'first_name' => 'Usuario',
                'last_name' => 'Número 1',
                'email' => 'admin@fundacion.com',
                'password' => bcrypt('123456'),
                'user_type' => config('constants.userRoles.user'),
            ],
        ];

        foreach ($initialUsers as $currentUser) {
            $user = User::create($currentUser);
            $user->assignRole(config('constants.userRolesString')[$currentUser['user_type']]);
        }
    }
}
