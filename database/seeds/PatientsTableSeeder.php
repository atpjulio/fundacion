<?php

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

    }
}
