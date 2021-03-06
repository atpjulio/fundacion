<?php

namespace App\Models\Merchants;

use App\Models\Eps\Eps;
use App\Models\Invoices\InvoiceSerie;
use App\Models\Patients\Companion;
use App\Models\Patients\Patient;
use App\Traits\HasAddress;
use App\Traits\ModelResults;
use App\Utils\Utilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use stdClass;

class Merchant extends Model
{
  use HasAddress, ModelResults;

  protected $hidden   = ['image'];
  protected $fillable = [
    'dni_type',
    'dni',
    'name',
    'alias',
    'phone1',
    'phone2',
    'image',
  ];
  private $sortField = 'name';

  /**
   * Attributes
   */

  public function getDniShortAttribute()
  {
    return substr($this->dni, 0, 9);
  }

  public function getDniWithZerosAttribute()
  {
    return str_replace($this->dni, '-', '0');
  }

  public function getImageUrlAttribute()
  {
    if (!$this->image) {
      return null;
    }
    return Storage::disk('public')->url($this->getImagePath($this->image));
  }

  /**
   * Relationships
   */

  public function activeSerie()
  {
    return $this->series()->active();
  }

  public function address()
  {
    return $this->hasOne(MerchantAddress::class, 'parent_id');
  }

  public function companions()
  {
    return $this->hasMany(Companion::class);
  }

  public function epss()
  {
    return $this->hasMany(Eps::class);
  }

  public function patients()
  {
    return $this->hasMany(Patient::class);
  }

  public function series()
  {
    return $this->hasMany(InvoiceSerie::class);
  }

  /**
   * Scopes
   */

  public function scopeSearch($query, $request)
  {
    $query->when($request->get('search'), function ($query, $search) {
      $query->where('name', 'like', "$search%");
    });
  }

  public function scopeSort($query, $request)
  {
    $query->when($request->get('sortDirection'), function ($query, $sortDirection) {
      $query->orderBy($this->sortField, $sortDirection);
    });
  }

  /**
   * Methods
   */

  protected function getLatestRecords(Request $request)
  {
    $query = $this->query()
      ->search($request)
      ->sort($request);

    return $this->paginateResult($request, $query);
  }

  protected function storeRecord($request)
  {
    $merchant = new Merchant($request->only($this->fillable));

    $merchant->save();
    $merchant->storeOrUpdateAddress($request);
    $merchant->storeSerie($request);
    $merchant->storeOrUpdateImage($request);

    return $merchant;
  }

  protected function getRecord($id)
  {
    $merchant = $this->with(['address', 'activeSerie'])
      ->where('id', $id)
      ->firstOrFail();

    if ($merchant) {
      $merchant->append('image_url');
    }
    return $merchant;
  }

  protected function updateRecord($request, $merchantId)
  {
    $merchant = $this->findOrFail($merchantId);

    $merchant->update($request->only($this->fillable));
    $merchant->storeOrUpdateAddress($request);
    $merchant->storeOrUpdateImage($request);

    return $merchant;
  }

  protected function deleteRecord($id)
  {
    $this->where('id', $id)
      ->delete();
  }

  /**
   * Invoice serie handling
   */

  public function storeSerie(Request $request)
  {
    return InvoiceSerie::storeRecord($request, $this->id);
  }

  public function updateSerie(Request $request)
  {
    return InvoiceSerie::updateRecord($request, $this->id, $request->get('serie_id'));
  }

  /**
   * Select handling
   */

  protected function getForSelect($whereHas = null, $defaultText = 'Todas las empresas', $withPrepend = true)
  {
    $defaultOption = new stdClass();

    $defaultOption->value = 0;
    $defaultOption->name = $defaultText;

    $query = $this->select('id as value', 'name');

    if ($whereHas) {
      $query->whereHas($whereHas);
    }

    $options = $query->orderBy($this->sortField)
      ->get();

    if ($withPrepend) {
      return $options->prepend($defaultOption);
    }
    return $options;
  }

  /**
   * Image handling
   */

  protected function getImagePath($fileName)
  {
    return 'merchants/images/' . $this->id . '_' . $fileName;
  }

  public function storeOrUpdateImage(Request $request)
  {
    if (!$request->has('file')) {
      return;
    }

    $this->deleteImage();

    $file     = $request->file('file');
    $fileName = Utilities::normalizeString($file->getClientOriginalName());
    $fullPath = $this->getImagePath($fileName);

    Storage::disk('public')->put($fullPath, file_get_contents($file));

    $this->update(['image' => $fileName]);
  }

  public function deleteImage()
  {
    if (empty($this->image)) {
      return;
    }

    Storage::disk('public')->delete($this->getImagePath($this->image));

    $this->update(['image' => null]);
  }
}
