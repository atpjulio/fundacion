<?php

use App\Address;
use App\Eps;
use App\Phone;
use Illuminate\Database\Seeder;

class EpsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear las EPS
        $epss = [
            [
                'code' => 'CCF023',
                'name' => 'Caja de Compensación Familiar de La Guajira',
                'nit' => '892115006-5',
                'daily_price' => 55000,
                'alias' => 'Comfaguajira',
            ],
            [
                'code' => 'EPSS33',
                'name' => 'Salud Vida EPS',
                'nit' => '830074184-5',
                'daily_price' => 50000,
                'alias' => 'Salud Vida',
            ],
            [
                'code' => 'CCF055',
                'name' => 'Cajacopi EPS-S',
                'nit' => '890102044-1',
                'daily_price' => 40000,
                'alias' => 'Cajacopi',
            ],
            [
                'code' => 'ESS133',
                'name' => 'Comparta EPS',
                'nit' => '804002105-0',
                'daily_price' => 55000,
                'alias' => 'Comparta',
            ],
            [
                'code' => 'ESS207',
                'name' => 'Asociación Mutual Ser E.S.S. - E.P.S.S.',
                'nit' => '806008394-7',
                'daily_price' => 40000,
                'alias' => 'Mutual Ser',
            ],
            [
                'code' => 'ESS076',
                'name' => 'Asociación Mutual Barrios Unidos de Quibdo E.S.S. - E.P.S.S.',
                'nit' => '818000140-0',
                'daily_price' => 45000,
                'alias' => 'Barrios Unidos',
            ],
        ];
        foreach ($epss as $eps) {
            Eps::create($eps);
        }
        // Crear las direcciones de las EPS
        $addresses = [
            [
                'model_id' => 1,
                'model_type' => config('constants.modelType.eps'),
                'address' => 'Calle 13 No 08-176',
                'city' => 'Barrancas',
                'state' => '44',
            ],
            [
                'model_id' => 2,
                'model_type' => config('constants.modelType.eps'),
                'address' => '304, Avenida Pedro Herdia, Cra. 44 #39',
                'city' => 'Cartagena',
                'state' => '13',
            ],
            [
                'model_id' => 3,
                'model_type' => config('constants.modelType.eps'),
                'address' => 'Carrera 19 No. 11-43',
                'city' => 'Valledupar',
                'state' => '20',
            ],
            [
                'model_id' => 4,
                'model_type' => config('constants.modelType.eps'),
                'address' => 'Carrera 28 No. 31-18 La Aurora',
                'city' => 'Bucaramanga',
                'state' => '68',
            ],
            [
                'model_id' => 5,
                'model_type' => config('constants.modelType.eps'),
                'address' => 'Av. Santander Car 1a No 41-56 El Cabrero',
                'city' => 'Cartagena',
                'state' => '13',
            ],
            [
                'model_id' => 6,
                'model_type' => config('constants.modelType.eps'),
                'address' => 'KR 51 No. 79-34 Edif. Ejecutivo',
                'address2' => 'Barranquilla OFIC. 207',
                'city' => 'Barranquilla',
                'state' => '08',
            ],
        ];
        foreach ($addresses as $address) {
            Address::create($address);
        }
        // Crear los teléfonos de las EPS
        $phones = [
            [
                'model_id' => 1,
                'model_type' => config('constants.modelType.eps'),
                'phone' => '7283620',
            ],
            [
                'model_id' => 2,
                'model_type' => config('constants.modelType.eps'),
                'phone' => '7272183',
            ],
            [
                'model_id' => 3,
                'model_type' => config('constants.modelType.eps'),
                'phone' => '5715390',
            ],
            [
                'model_id' => 4,
                'model_type' => config('constants.modelType.eps'),
                'phone' => '6977858',
            ],
            [
                'model_id' => 5,
                'model_type' => config('constants.modelType.eps'),
                'phone' => '6502525',
            ],
            [
                'model_id' => 6,
                'model_type' => config('constants.modelType.eps'),
                'phone' => '3369121',
            ],
        ];
        foreach ($phones as $phone) {
            Phone::create($phone);
        }
    }
}
