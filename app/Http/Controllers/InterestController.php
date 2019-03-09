<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Interest;
use App\Transaction;

class InterestController extends Controller
{
    public function create()
    {
        $transaction = new Transaction;
        $transaction->from = request()->from;
        $transaction->to = '1';
        $transaction->amount = request()->amount;
        $transaction->type = request()->type;

        $balanceCheck = $transaction->checkBalance();

        if($balanceCheck)
        {
            $interest = new Interest;                   //record interest
            $interest->address = $transaction->from;
            $interest->amount = $transaction->amount;
            $interest->save();
            $result = $interest;

            $transaction->makeTransaction();            //record interest in transactions
        }
        else
        {
            $result = json_encode('Not enough balance.');
        }
        return response($result, 200)->header('Content-type', 'application/json');

    }

    public function show($uid)
    {
    	$interest = Transaction::where('', $uid)->get();
    	return response($interest, 200)->header('Content-type', 'application/json');
    }

    public function showInterest($id)
    {
        $interest = Interest::find($id);
        $interestRecord = $interest->getInterests();

        return $interestRecord;
    }
}
