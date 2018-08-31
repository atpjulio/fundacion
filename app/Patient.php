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
            'first_name' => ucwords(strtolower($request->get('first_name'))),
            'last_name' => ucwords(strtolower($request->get('last_name'))),
            'birth_date' => $birthDate,
            'gender' => $request->get('gender'),
            'type' => $request->get('type'),
            'state' => $request->get('state'),
            'city' => $request->get('city'),
            'zone' => $request->get('zone'),
        ]);

        /*
        $address = new Address();

        $address->model_type = config('constants.modelType.patient');
        $address->model_id = $patient->id;
        $address->address = ucwords(strtolower($request->get('address')));
        $address->address2 = ucwords(strtolower($request->get('address2')));
        $address->city = ucwords(strtolower($request->get('city')));
        $address->state = $request->get('state');

        $address->save();

        $phone = new Phone();

        $phone->model_type = config('constants.modelType.patient');
        $phone->model_id = $patient->id;
        $phone->phone = $request->get('phone');
        $phone->phone2 = $request->get('phone2');

        $phone->save();
        */
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
                'first_name' => ucwords(strtolower($request->get('first_name'))),
                'last_name' => ucwords(strtolower($request->get('last_name'))),
                'birth_date' => $birthDate,
                'gender' => $request->get('gender'),
                'type' => $request->get('type'),
                'state' => $request->get('state'),
                'city' => $request->get('city'),
                'zone' => $request->get('zone'),
            ]);
            /*
            $patient->address->update([
                'address' => ucwords(strtolower($request->get('address'))),
                'address2' => ucwords(strtolower($request->get('address2'))),
                'city' => ucwords(strtolower($request->get('city'))),
                'state' => $request->get('state'),
            ]);

            $patient->phone->update([
                'phone' => $request->get('phone'),
                'phone2' => $request->get('phone2'),
            ]);
            */
        }

        return $patient;
    }

    protected function checkIfExists($dni, $dniType = null)
    {
        if ($dniType) {
            return $this->where("dni", trim($dni))
                ->where("dni_type", trim(strtoupper($dniType)))
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
                'first_name' => ucwords(strtolower($firstName)),
                'last_name' => ucwords(strtolower($lastName)),
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

    protected function getPatientsForEps($epsId)
    {
        return $this->where('eps_id', $epsId)
            ->get();
    }
}
