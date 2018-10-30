<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class City extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'state_code',
        'code',
        'name',
    ];

    /**
     * Relations
     */
    public function state()
    {
        return $this->hasOne(State::class, 'code', 'state_code');
    }

    /**
     * Methods
     */
    protected function createCities()
    {
        Excel::load('public/files/'.config('constants.citiesFilename'), function($reader) {
            $states = State::all();
            foreach ($states as $state) {
                foreach ($reader->get() as $line) {
                    if ($line->codigo_deapartamento == $state->code) {
                        $this->create([
                            'state_code' => $state->code,
                            'code' => $line->codigo_municipio,
                            'name' => $line->nombre_municipio,
                        ]);
                    }
                }
            }
        });
    }

    protected function getCitiesByStateId($stateCode)
    {
        return $this->where('state_code', $stateCode)
            ->get()
            ->pluck('name', 'code');
    }

    protected function getCityByCode($code) 
    {
        $result = $this->where('code', $code)
            ->first();

        return $result ? $result->name : "";
    }

    protected function getCityByCodeAndState($stateCode, $code) 
    {
        $result = $this->where('code', $code)
            ->where('state_code', $stateCode)
            ->first();

        return $result ? $result->name : "";
    }
}
