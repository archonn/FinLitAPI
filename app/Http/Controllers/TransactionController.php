<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\User;
use App\Notifications\NotifyTransaction;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
    	$transaction = new Transaction;
    	$transaction->amount = request()->amount;
    	$transaction->to = request()->to;
    	$transaction->from = request()->from;

    	$balanceCheck = $transaction->checkBalance();	//balance check

    	if($balanceCheck)
    	{
    		$result = $transaction->makeTransaction();

            //notify receiver about transaction
            $user = User::where('address', $result->to)->first(); 
            $user->notify(new NotifyTransaction($result));
            

    		return response($result, 200)->header('Content-type', 'application/json');
    	}
    	else
    	{
    		$result = json_encode('Not enough balance');
    		return response($result, 200)->header('Content-type', 'application/json');
    	}

    }

    public function show($id)
    {
    	$transaction = Transaction::find($id);
    	return response($transaction, 200)->header('Content-type', 'application/json');
    }

    public function showByUser($address)
    {
    	$data = Transaction::where('from', $address)->orWhere('to', $address)->get();

        foreach($data as $d)
        {
            $user = User::where('address', $d->to)->first();
            $d['to'] = $user->name;

            $user = User::where('address', $d->from)->first();
            $d['from'] = $user->name;
        }

    	return response($data, 200)->header('Content-type', 'application/json');
    }
}
