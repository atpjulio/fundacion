<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AuthorizationDate extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'authorization_id',
    'date_from',
    'date_to',
    ];
    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    /**
    * Relationships
    */
    public function authorization()
    {
        return $this->belongsTo(Authorization::class);
    }

    /**
    * Dynamic attributes
    */
    public function getDaysAttribute()
    {
        return \Carbon\Carbon::parse($this->date_to)->diffInDays(\Carbon\Carbon::parse($this->date_from));
    }


    protected function storeRecord($authorization, $request)
    {
        return $this->create([
          'authorization_id' => $authorization->id,
          'date_from' => $request->get('date_from'),
          'date_to' => \Carbon\Carbon::parse($request->get('date_from'))->addDays($request->get('total_days'))->format("Y-m-d"),
        ]);
    }

    protected function updateRecord($authorization, $request)
    {
        DB::table('authorization_dates')
            ->where('authorization_id', $authorization->id)
            ->delete();

        return $this->create([
            'authorization_id' => $authorization->id,
            'date_from' => $request->get('date_from'),
            'date_to' => \Carbon\Carbon::parse($request->get('date_from'))->addDays($request->get('total_days'))->format("Y-m-d"),
        ]);
    }
}
