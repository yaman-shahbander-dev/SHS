<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;


use App\DataTables\ChatDataTable;
use App\Repositories\ChatRepository;
use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\MessageRepository;
use Flash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ChatController extends Controller
{
    
    private $chatRepository;
    private $userRepository;
    private $orderRepository;
    private $restaurantRepository;
    //private $messageRepository;
    
    
    public function __construct(ChatRepository $chatRepo,UserRepository $userRepo,OrderRepository $orderRepo,RestaurantRepository $restaurantRepo)
    {
        parent::__construct();
        $this->chatRepository = $chatRepo;
        $this->userRepository = $userRepo;
        $this->orderRepository = $orderRepo;
        $this->restaurantRepository = $restaurantRepo;
        //$this->messageRepository = $messageRepo;
    }
    
    
    public function index(ChatDataTable $chatDataTable)
    {
        //$chats = Chat::all();
        //dd($chatDataTable);
        return $chatDataTable->render('chats.index');
    }
    
    public function view_chat($id)
    {
        $messages = Message::where('chat_id',$id)->with('user')->get();
        $chat = Chat::where('id',$id)->with('restaurant')->with('order')->first();
        $restaurant_name = $chat->restaurant->name;
        $order_id = $chat->order->id;
        
        return view('chats.view_chat',compact('messages','restaurant_name','order_id'));
    }
}
