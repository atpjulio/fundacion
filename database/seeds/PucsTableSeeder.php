<?php

use App\Puc;
use Illuminate\Database\Seeder;

class PucsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Puc::createPucs();
    }
}
