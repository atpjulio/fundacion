<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $fillable = [
    	'name',
    	'doc',
    	'address',
    	'phone',
    ];

    /**
     * Relationships
     */

    /**
     * Methods
     */
	protected function checkIfExists($doc)
	{
		return $this->where('doc', $doc)
			->first();
	}

	protected function storeRecord($request)
	{
		$entity = new Entity();

		$entity->name = ucwords(mb_strtolower($request->get('name')));
		$entity->doc = $request->get('doc') ?: $request->get('nit');
		$entity->address = ucwords(mb_strtolower($request->get('address')));
		$entity->phone = $request->get('phone');

		$entity->save();

		return $entity;
	}

	protected function updateRecord($request, $doc = null)
	{
		if ($request->get('entity_id')) {
			$entity = $this->find($request->get('entity_id'));
		} else {
			$entity = $this->checkIfExists($doc);
		}

		if ($entity) {
			$entity->name = ucwords(mb_strtolower($request->get('name')));
			$entity->doc = $request->get('doc') ?: $request->get('nit');
			$entity->address = ucwords(mb_strtolower($request->get('address')));
			$entity->phone = $request->get('phone');

			$entity->save();
		}

		return $entity;
	}
}
