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

		$entity->name = $request->get('name');
		$entity->doc = $request->get('doc');
		$entity->address = $request->get('address');
		$entity->phone = $request->get('phone');

		$entity->save();

		return $entity;
	}

	protected function updateRecord($request)
	{
		$entity = $this->find($request->get('entity_id'));

		if ($entity) {
			$entity->name = $request->get('name');
			$entity->doc = $request->get('doc');
			$entity->address = $request->get('address');
			$entity->phone = $request->get('phone');

			$entity->save();
		}

		return $entity;
	}
}
