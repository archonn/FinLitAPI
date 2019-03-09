<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $fillable = [
    	'address', 'amount', 'status'
    ];

    protected $attributes = [
    	'status' => 'deposited'
    ];

    public function getInterests()
    {
    	$datediff = $this->created_at->diffInDays(now());

    	$interestTimes = $datediff / 5;

    	$interestRecord = [];
		$ir = [];
		$ir['total'] = $this->amount;
		$ir['date'] = $this->created_at;

    	for($i = 0; $i<(int)$interestTimes; $i++)
    	{
    		$ir['date'] = $ir['date']->addDays(5);
    		$ir['amount'] = $ir['total'];
    		$ir['interest'] = ceil($ir['amount'] * 0.08);
    		$ir['total'] = $ir['amount'] + $ir['interest'];

    		array_push($interestRecord, $ir);
    	}
    	return $interestRecord;
    }
}
