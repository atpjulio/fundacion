<?php

namespace App\Models\Invoices;

use App\Models\Merchants\Merchant;
use App\Traits\ModelResults;
use App\Traits\ScopeSearch;
use App\Traits\ScopeSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InvoiceSerie extends Model
{
	use ScopeSearch, ScopeSort, ModelResults;

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
	private $sortField = 'name';

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

	protected function getLatestRecords(Request $request, $merchantId)
  {
    $query = $this->where('merchant_id', $merchantId)
      ->search(['name'], $request->get('search'))
      ->sort($request);

    return $this->paginateResult($request, $query);
  }

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
			->where('id', $serieId)
			->firstOrFail();

		$serie->fill($request->all());
		$serie->save();

		return $serie;
	}

	protected function deleteRecord($id)
  {
    $this->where('id', $id)
      ->delete();
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
