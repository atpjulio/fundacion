<?php

use App\Address;
use App\Company;
use App\Phone;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear la compañía
        $company = [
            'name' => 'Fundación Multiactiva Casa Hogar el Milagro',
            'nit' => '900254478-1',
            'billing_resolution' => '13028009806159',
            'billing_date' => '2018-05-29',
            'billing_start' => 7000,
            'billing_end' => 12000,
            'alias' => 'Fundación',
        ];
        Company::create($company);
        // Crear la dirección
        $address = [
            'model_id' => 7,
            'model_type' => config('constants.modelType.company'),
            'address' => 'Cl 60 No 46-76 Brr Boston',
            'city' => '001',
            'state' => '08',
        ];
        Address::create($address);
        // Crear el teléfono
        $phone = [
            'model_id' => 7,
            'model_type' => config('constants.modelType.company'),
            'phone' => '3126214231',
        ];
        Phone::create($phone);
    }
}
