<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'eps_id',
        'dni_type',
        'dni',
        'first_name',
        'last_name',
        'email',
        'birth_date',
        'gender',
        'type',
        'state',
        'city',
        'zone',
        'notes',
    ];

    /**
     * Attritbutes
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAgeAttribute()
    {
        return \Carbon\Carbon::createFromDate(
            substr($this->birth_date,0,4),
            substr($this->birth_date,5,2),
            substr($this->birth_date,8,2))->age;
    }

    /**
     * Relations
     */
    public function address()
    {
        return $this->hasOne(Address::class, 'model_id')
            ->where('model_type', config('constants.modelType.patient'));
    }

    public function phone()
    {
        return $this->hasOne(Phone::class, 'model_id')
            ->where('model_type', config('constants.modelType.patient'));
    }

    public function eps()
    {
        return $this->hasOne(Eps::class, 'id', 'eps_id');
    }

    /**
     * Methods
     */
    protected function storeRecord($request)
    {
        $birthDate = $request->get('birth_year').'-'.
            sprintf("%02d",$request->get('birth_month')).'-'.
            sprintf("%02d",$request->get('birth_day'));

        $patient = $this->create([
            'eps_id' => $request->get('eps_id'),
            'dni_type' => $request->get('dni_type'),
            'dni' => strtoupper($request->get('dni')),
            'first_name' => ucwords(mb_strtolower($request->get('first_name'))),
            'last_name' => ucwords(mb_strtolower($request->get('last_name'))),
            'birth_date' => $birthDate,
            'gender' => $request->get('gender'),
            'type' => $request->get('type'),
            'state' => $request->get('state'),
            'city' => $request->get('city'),
            'zone' => $request->get('zone'),
        ]);

        return $patient;
    }

    protected function updateRecord($request)
    {
        $patient = $this->find($request->get('id'));

        if ($patient) {
            $birthDate = $request->get('birth_year').'-'.
                sprintf("%02d",$request->get('birth_month')).'-'.
                sprintf("%02d",$request->get('birth_day'));

            $patient->update([
                'eps_id' => $request->get('eps_id'),
                'dni_type' => $request->get('dni_type'),
                'dni' => strtoupper($request->get('dni')),
                'first_name' => ucwords(mb_strtolower($request->get('first_name'))),
                'last_name' => ucwords(mb_strtolower($request->get('last_name'))),
                'birth_date' => $birthDate,
                'gender' => $request->get('gender'),
                'type' => $request->get('type'),
                'state' => $request->get('state'),
                'city' => $request->get('city'),
                'zone' => $request->get('zone'),
            ]);
        }

        return $patient;
    }

    protected function checkIfExists($dni, $dniType = null)
    {
        if ($dniType) {
            return $this->where("dni", trim($dni))
                ->where("dni_type", trim(mb_strtoupper($dniType)))
                ->first();
        }

        return $this->where("dni", $dni)
            ->first();
    }

    protected function storeRecordFromExcel($line)
    {
        $dni = intval($line->numero_de_identifiacion_del_usuario_en_el_sistema).'';
        $dniType = $line->tipo_de_identificacion_del_usuario;
        $epsCode = $line->codigo_entidad_administradora;
        $patient = null;

        $eps = Eps::checkIfExists($epsCode);

        if (!$this->checkIfExists($dni, $dniType) and $eps) {
            $firstName = $line->primer_nombre_del_usuario.' '.$line->segundo_nombre_del_usuario;
            $lastName = $line->primer_apellido_del_usuario.' '.$line->segundo_apellido_del_usuario;
            $birthDate = \Carbon\Carbon::createFromDate(date("Y") - intval($line->edad), date("m"), date("d"))
                ->format("Y-m-d");

            $patient = $this->create([
                'eps_id' => $eps->id,
                'dni_type' => strtoupper($dniType),
                'dni' => strtoupper($dni),
                'first_name' => ucwords(mb_strtolower($firstName)),
                'last_name' => ucwords(mb_strtolower($lastName)),
                'birth_date' => $birthDate,
                'gender' => ($line->sexo == 'F') ? 0 : 1,
                'type' => intval($line->tipo_de_usuario),
                'state' => intval($line->codigo_del_departamento_de_residencia_habitual).'',
                'city' => intval($line->codigo_de_municipios_de_residencia_habitual).'',
                'zone' => $line->zona_de_residencia_habitual,
            ]);

        }
        return $patient;
    }

    protected function storeRecordFromTxt($line, $epsId)
    {
        $data = explode(",", $line);
        $dniType = $data[1];
        $dni = $data[2];

        $patient = null;
        if (!$this->checkIfExists($dni, $dniType)) {
            $lastName = trim($data[3].' '.$data[4]);
            $firstName = trim($data[5].' '.$data[6]);
            $birthDate = \DateTime::createFromFormat('d/m/Y', $data[7])->format('Y-m-d');

            $patient = $this->create([
                'eps_id' => $epsId,
                'dni_type' => strtoupper($dniType),
                'dni' => strtoupper($dni),
                'first_name' => ucwords(mb_strtolower($firstName)),
                'last_name' => ucwords(mb_strtolower($lastName)),
                'birth_date' => $birthDate,
                'gender' => ($data[8] == 'F') ? 0 : 1,
                'type' => 2,
                'state' => sprintf("%02d", $data[11]),
                'city' => sprintf("%03d", $data[12]),
                'zone' => $data[13],
            ]);

            $address = Address::create([
                'model_id' => $patient->id,
                'model_type' => config('constants.modelType.patient'),
                'address' => substr(ucwords(mb_strtolower($data[17])), 0, 50),
                'address2' => substr(ucwords(mb_strtolower($data[20])), 0, 50),
                'state' => sprintf("%02d", $data[11]),
                'city' => sprintf("%03d", $data[12]),
            ]);

            if (strlen($data[18]) > 1 and is_numeric($data[18])) {
                $phones = Phone::create([
                    'model_id' => $patient->id,
                    'model_type' => config('constants.modelType.patient'),
                    'phone' => $data[18],
                ]);
            }
        }
        return $patient;
    }

    protected function getPatientsForEps($epsId)
    {
        return $this->where('eps_id', $epsId)
            ->get();
    }
}
