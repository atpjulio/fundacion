<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'doc_type',
        'doc',
        'billing_resolution',
        'billing_date',
        'billing_start',
        'billing_end',
        'alias',
        'logo',
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
    public function address()
    {
        return $this->hasOne(Address::class, 'model_id')
            ->where('model_type', config('constants.modelType.company'));
    }

    public function phone()
    {
        return $this->hasOne(Phone::class, 'model_id')
            ->where('model_type', config('constants.modelType.company'));
    }

    /**
     * Methods
     */
    protected function storeRecord($request) 
    {
        $company = new Company();

        $company->name = $request->get('name');
        $company->doc = $request->get('doc');
        $company->billing_resolution = $request->get('billing_resolution');
        $company->billing_date = $request->get('billing_date');
        $company->billing_start = $request->get('billing_start');
        $company->billing_end = $request->get('billing_end');
        $company->alias = $request->get('alias');
        $company->notes = $request->get('notes');

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time().'_'.$file->getClientOriginalName();

            $file->move(public_path().config('constants.companiesImages'), $fileName);
            
            $company->logo = config('constants.companiesImages').$fileName;
        }

        $company->save();

        $address = new Address();

        $address->model_type = config('constants.modelType.company');
        $address->model_id = $company->id;
        $address->address = ucwords(strtolower($request->get('address')));
        $address->address2 = ucwords(strtolower($request->get('address2')));
        $address->city = ucwords(strtolower($request->get('city')));
        $address->state = $request->get('state');

        $address->save();

        $phone = new Phone();

        $phone->model_type = config('constants.modelType.company');
        $phone->model_id = $company->id;
        $phone->phone = $request->get('phone');
        $phone->phone2 = $request->get('phone2');

        $phone->save();

        return $company;
    }

    protected function updateRecord($request) 
    {
        $company = $this->find($request->get('id'));

        if ($company) {
            $company->name = $request->get('name');
            $company->doc = $request->get('doc');
            $company->billing_resolution = $request->get('billing_resolution');
            $company->billing_date = $request->get('billing_date');
            $company->billing_start = $request->get('billing_start');
            $company->billing_end = $request->get('billing_end');
            $company->alias = $request->get('alias');
            $company->notes = $request->get('notes');

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time().'_'.$file->getClientOriginalName();

                $file->move(public_path().config('constants.companiesImages'), $fileName);
                
                $company->logo = config('constants.companiesImages').$fileName;
            }

            $company->save();

            $company->address->update([
                'address' => ucwords(strtolower($request->get('address'))),
                'address2' => ucwords(strtolower($request->get('address2'))),
                'city' => ucwords(strtolower($request->get('city'))),
                'state' => $request->get('state'),
            ]);

            $company->phone->update([
                'phone' => $request->get('phone'),
                'phone2' => $request->get('phone2'),
            ]);
        }

        return $company;
    }
}
