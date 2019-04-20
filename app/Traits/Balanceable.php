<?php

namespace App\Traits;

use App\Balance;

trait Balanceable
{
	public function createBalance($balanceable, $amount, $month, $year, $type)
	{
        $balance = new Balance();

        $balance->balanceable_type = $balanceable->getMorphClass();
        $balance->balanceable_id = $balanceable->getKey();
        $balance->amount = $amount;
        $balance->month = $month;
        $balance->year = $year;
        $balance->type = $type;

        $balance->save();

		return $balance;
	}

	public function updateBalance($balance, $amount, $month, $year, $type)
	{
        $balance->update([
            'amount' => $amount,
            'month' => $month,
            'year' => $year,
            'type' => $type,
        ]);

        // $balance->amount = $amount;
        // $balance->month = $month;
        // $balance->year = $year;
        // $balance->type = $type;

        // $balance->save();

		return $balance;
	}


}