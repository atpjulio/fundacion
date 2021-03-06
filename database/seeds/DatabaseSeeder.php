<?php

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
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(EpsTableSeeder::class);
        $this->call(EpsServicesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(PatientsTableSeeder::class);
        // $this->call(AuthorizationsTableSeeder::class);
        // $this->call(EntitiesTableSeeder::class);
        $this->call(PucsTableSeeder::class);
    }
}
