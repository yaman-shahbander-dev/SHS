<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TransferTransaction;
use App\Balance;
use App\Repositories\TransferTransactionRepository;
use DB;



class MoneyAPIController extends Controller
{

    function humanTiming ($time) {
        $time = time() - $time;
        // to get the time since that moment $time = ($time<1)? 1 : $time;
        $tokens = array ( 31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second' );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
         }
      }

    public function history(Request $request) {
        if($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }
            $response = [];

            $transactionsHistory = $user->ToUserName->transform(function($q){
              $q['amount']       = round($q->pivot->amount,2);
              $q['payment_id']   = $q->payment_id;
              $q['payment_time'] = $this->humanTiming(strtotime($q->pivot->created_at));
              $q['type']         = $q->pivot->type;
              return $q->only('amount', 'payment_id', 'payment_time', 'type');

            });

            return $this->sendResponse($transactionsHistory->toArray(), 'History retrieved successfully');
        }
    }


    public function transferMoney(Request $request) {
        try {
            if($request->header('devicetoken')) {

                $user = User::where('device_token', $request->header('devicetoken'))->first();

                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $vendor = User::where('payment_id', $request->vendor_payment_id)->first();

                $amount  = strip_tags($request->amount);

                //If the field has any character
                if(preg_match('/[a-zA-Z]/', $amount)) { 
                    return $this->sendError('The field must contain only numbers', 401);
                 }
                 
                 //If the field has any special character
                 if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $amount)) { 
                    return $this->sendError('The field must contain only numbers', 401);
                }
                // Transfering to the same user
                if ($user->payment_id == $request->vendor_payment_id) {
                    return $this->sendError('you cant transfer money to yourself ', 401);
                }

                //Vendor has no balance account
                if ($vendor->balance_id == null) {
                    return $this->sendError('There is no balance in vendor account', 401);
                }

                //homeowner has no balance account
                if ($user->balance_id == null) {
                    return $this->sendError('There is no balance in your account', 401);
                }

                //amount is empty
                if (empty($amount)) {
                    return $this->sendError('Transfer failed! amount should have a value', 401);
                 }

                 //amount should not be negative
                 if ($amount < 0) {
                    return $this->sendError('Transfer failed! amount should not be negative', 401);
                 } 
                

                if ($user->Balance->balance - $amount >= 0) {
                    $user->Balance->balance   = $user->Balance->balance - $amount;
                    $vendor->Balance->balance = $vendor->Balance->balance + $amount;
                    $user->Balance->save();
                    $vendor->Balance->save();

                    $transfer=[['from_id'=>$user->id,'to_id'=>$vendor->id,'amount'=>$amount]];

                    $user->ToUserName()->attach($transfer);


                    return $this->sendResponse([], 'Money transfered successfully');
                } else {
                    return $this->sendError('There is no enough balance in your account', 401);
                }
            }
      } catch (\Exception $e) {
          return $this->sendError('Something is wrong', 401);
      }
    }

    public function currentBalance(Request $request) {
        try {
            if($request->header('devicetoken')) {

                $user = User::where('device_token', $request->header('devicetoken'))->first();

                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $userBalance   = $user->Balance;


                if ($userBalance == null) return $this->sendError( 'No balance for the current user',401);

                $response = [
                    'balance' => $userBalance->balance
                ];

                return $this->sendResponse($response, 'Balance retrieved successfuly!');
            }
    } catch(\Exception $e) {
        return $this->sendError('Error', 401);
    }
  }

}
