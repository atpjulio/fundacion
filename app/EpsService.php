<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EpsService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'eps_id',
        'code',
        'name',
        'notes',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Relations
     */


    /**
     * Methods
     */
    protected function getServices($id)
    {
        return $this->where('eps_id', $id)
            ->orderBy('code')
            ->get();
    }

    protected function storeRecord($request)
    {
        return $this->create([
            'eps_id' => $request->get('eps_id'),
            'code' => strtoupper($request->get('code')),
            'name' => ucfirst(mb_strtolower($request->get('name'))),
            'notes' => $request->get('notes'),
        ]);
    }

    protected function updateRecord($request)
    {
        $service = $this->find($request->get('id'));

        if ($service) {
            $service->update([
                'eps_id' => $request->get('eps_id'),
                'code' => strtoupper($request->get('code')),
                'name' => ucfirst(mb_strtolower($request->get('name'))),
                'notes' => $request->get('notes'),
            ]);
        }

        return $service;
    }
}
