<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Chat;

class MessageApiController extends Controller
{
    public function store(Request $request)
    {
        // message - sender_id - chat_id
        // order_id - restaurant_id - sender_id  - message
        
        try{
            $is_customer = $request->input('is_customer');;
            $restaurant_id = $request->input('restaurant_id');
            $order_id = $request->input('order_id');
            $chat = Chat::where('restaurant_id',$restaurant_id)->where('order_id',$order_id)->first();
            $chat_id = $chat->id;
            $sender_id = $request->input('sender_id');
            $msg = $request->input('message');
            $message = new Message();
            $message->chat_id = $chat_id;
            $message->sender_id = $sender_id;
            $message->msg = $msg;
            if(isset($is_customer))
            {
                $message->is_customer = $is_customer;
            }
            $message->save();
            
            return $this->sendResponse(true, 'message created successfully');
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        
    }
}
