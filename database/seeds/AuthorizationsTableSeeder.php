<?php

use App\Authorization;
use Illuminate\Database\Seeder;

class AuthorizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'eps_id' => 3,
            'eps_service_id' => 2,
            'patient_id' => 5,
            'code' => '2000100315614',
            'date_from' => '2018-03-23',
            'date_to' => '2018-05-22',
        ];

        Authorization::insert($data);
    }
}
