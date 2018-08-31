<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Puc extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    /**
     * Methods
     */
    protected function createPucs()
    {
        Excel::load('public/files/'.config('constants.pucsFilename'), function($reader) {
            foreach ($reader->get() as $line) {
                $this->create([
                    'code' => $line->code,
                    'description' => $line->description,
                ]);
            }
        });
    }

    protected function getPuc($code)
    {
        return $this->where('code', $code)
            ->first();
    }

}
