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

    protected function updatePuc($code, $description) 
    {
        $puc = $this->where('code')->first();

        if (!$puc) {
            $puc = new Puc();

            $puc->code = $code;
            $puc->description = $description;

            $puc->save();

            return;
        } 

        if (strcmp($puc->description, $description) !== 0) {
            $puc->update([
                'description' => $description
            ]);
        }
    }
}
