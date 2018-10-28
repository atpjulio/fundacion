<?php

use App\Entity;
use App\Patient;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Facades\Excel;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $initialPatients = [
            [
                'eps_id' => 2,
                'first_name' => 'Ana Isabel',
                'last_name' => 'Doria Tordecilla',
                'dni_type' => 'CC',
                'dni' => 1123992569,
                'birth_date' => '1986-01-10',
                'gender' => 0,
                'type' => 2,
            ],
            [
                'eps_id' => 2,
                'first_name' => 'Luis Miguel',
                'last_name' => 'Larios Pertuz',
                'dni_type' => 'TI',
                'dni' => 1082858383,
                'birth_date' => '2005-01-10',
                'gender' => 1,
                'type' => 2,
            ],
            [
                'eps_id' => 1,
                'first_name' => 'Deibis Angélica',
                'last_name' => 'Galindo Cantillo',
                'dni_type' => 'CC',
                'dni' => 1122808103,
                'birth_date' => '1976-01-10',
                'gender' => 0,
                'type' => 2,
            ],
            [
                'eps_id' => 4,
                'first_name' => 'Libia',
                'last_name' => 'Gómez Martínez',
                'dni_type' => 'CC',
                'dni' => 45478526,
                'birth_date' => '1966-01-10',
                'gender' => 0,
                'type' => 2,
            ],
            [
                'eps_id' => 3,
                'first_name' => 'José Andrés',
                'last_name' => 'Daza Rodríguez',
                'dni_type' => 'RC',
                'dni' => 1067619344,
                'birth_date' => '2012-07-18',
                'gender' => 1,
                'type' => 2,
            ],
        ];

        foreach ($initialPatients as $patient) {
            Patient::create($patient);
        }
        */
        Excel::load(public_path('/files/initial_patients.xls'), function($reader) {
            $counter = 0;
            $data = $reader->get() instanceof RowCollection ? $reader->get() : $reader->get()->first();
            foreach ($data as $line) {
                $result = Patient::storeRecordFromExcel($line);
                if ($result) {
                    $counter++;
                }
            }
            if ($counter > 0) {
                echo "Se guardaron $counter usuarios exitosamente!\n";
            } else {
                echo "No se guardó ningún usuario. Es posible que ya estén guardados en el sistema\n";
            }
        });
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
