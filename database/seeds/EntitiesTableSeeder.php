<?php

use App\Entity;
use Illuminate\Database\Seeder;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $epss = [
            [
                'name' => 'Caja de Compensación Familiar de La Guajira',
                'doc' => '892115006-5',
                'address' => 'Calle 13 No 08-176',
                'phone' => '7283620',
            ],
            [
                'name' => 'Salud Vida EPS',
                'doc' => '830074184-5',
                'address' => '304, Avenida Pedro Herdia, Cra. 44 #39',
                'phone' => '7272183',
            ],
            [
                'name' => 'Cajacopi EPS-S',
                'doc' => '890102044-1',
                'address' => 'Carrera 19 No. 11-43',
                'phone' => '5715390',
            ],
            [
                'name' => 'Comparta EPS',
                'doc' => '804002105-0',
                'address' => 'Carrera 28 No. 31-18 La Aurora',
                'phone' => '6977858',
            ],
            [
                'name' => 'Asociación Mutual Ser E.S.S. - E.P.S.S.',
                'doc' => '806008394-7',
                'address' => 'Av. Santander Car 1a No 41-56 El Cabrero',
                'phone' => '6502525',
            ],
            [
                'name' => 'Asociación Mutual Barrios Unidos de Quibdo E.S.S. - E.P.S.S.',
                'doc' => '818000140-0',
                'address' => 'KR 51 No. 79-34 Edif. Ejecutivo Barranquilla OFIC. 207',
                'phone' => '3369121',
            ],
        ];
        foreach ($epss as $eps) {
            Entity::create($eps);
        }
    }
}
