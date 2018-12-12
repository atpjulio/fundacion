<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EpsPrice extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'eps_id',
    'name',
    'daily_price',
  ];
  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  public function authorization()
  {
    return $this->belongsTo(Eps::class);
  }
}
