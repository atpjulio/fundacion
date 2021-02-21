<?php

namespace App\Models\Invoices;

use App\Models\Merchants\Merchant;
use App\Traits\ScopeActive;
use Illuminate\Database\Eloquent\Model;

class InvoiceSerie extends Model
{
	use ScopeActive;

	protected $fillable = [
		'merchant_id',
		'resolution',
		'resolution_date',
		'name',
		'prefix',
		'number',
		'number_from',
		'number_to',
		'status',
	];

	/**
	 * Attributes
	 */

	/**
	 * Relationships
	 */

	public function merchant()
	{
		return $this->belongsTo(Merchant::class);
	}

	/**
	 * Scopes
	 */

	/**
	 * Methods
	 */

	protected function storeRecord($request, $merchantId)
	{
		$serie = new InvoiceSerie($request->all());

		$serie->name        = $request->get('name') ?? 'Primera serie de facturas';
		$serie->status      = $request->get('status') ?? config('enum.status.active');
		$serie->merchant_id = $merchantId;

		$serie->save();

		return $serie;
	}

	protected function updateRecord($request, $merchantId, $serieId)
	{
		$serie = $this->where('merchant_id', $merchantId)
			->firstOrFail($serieId);

		$serie->fill($request->all());
		$serie->save();

		return $serie;
	}

	public function setOtherStatusToInactive()
	{
		$this->query()
			->where('id', '<>', $this->id)
			->where('merchant_id', $this->merchant_id)
			->update(['status' => config('enum.status.inactive')]);
	}

	public function increaseNumber()
	{
		if ($this->number < 1) {
			$this->update(['number' => Invoice::getLastNumber($this->merchant_id, $this->id) + 1]);
		} else {
			$this->update(['number' => $this->number + 1]);
		}
	}
}
