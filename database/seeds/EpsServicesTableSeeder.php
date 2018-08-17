<?php

use App\EpsService;
use Illuminate\Database\Seeder;

class EpsServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'eps_id' => 1,
                'code' => 'Z003',
                'name' => 'Hospedaje Integral',
            ],
            [
                'eps_id' => 3,
                'code' => 'ALB-001',
                'name' => 'Albergue',
            ],
        ];

        foreach ($data as $service) {
            EpsService::create($service);
        }
    }
}
