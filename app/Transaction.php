<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Transaction extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'to', 'from', 'type'
    ];

    protected $attributes = [
        'type' => 'Transaction'
    ];

    public function checkBalance()
    {
    	$user = User::where('address', $this->from)->first();

    	if($user->balance < $this->amount)
    	{
    		return false;
    	}

    	return true;
    }

    public function makeTransaction()
    {
    	$this->save();

        if($this->type == 'Deposit')
        {
            $user = User::where('address', $this->from)->first();
            $user->balance -= $this->amount;
            $user->update();
        }
        else
        {
            $user = User::where('address', $this->to)->first();
            $user->balance += $this->amount;
            $user->update();

            $user = User::where('address', $this->from)->first();
            $user->balance -= $this->amount;
            $user->update();
        }
    	
    	return $this;
    }
}
