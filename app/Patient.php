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
}
