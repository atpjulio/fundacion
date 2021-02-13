<?php

namespace App\Models\Invoices;

use App\Models\Authorizations\Authorization;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
  protected $fillable = [
    'invoice_id',
    'authorization_id',
    'authorization_code',
    'service_code',
    'service_name',
    'quantity',
    'amount',
  ];

  /**
   * Relationships
   */

  public function invoice()
  {
    return $this->belongsTo(Invoice::class);
  }

  public function authorization()
  {
    return $this->belongsTo(Authorization::class);
  }

  /**
   * Scopes
   */

  /**
   * Methods
   */
}
