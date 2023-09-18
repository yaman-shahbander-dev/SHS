<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WebToPay;
use WebToPayException;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use Session;
use Illuminate\Foundation\Application;
use App;
use Flash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return redirect('/');


    }

    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAA4D63pNE:APA91bHZnOMtZp1E5zvs5hmd0mniLA2JRWQwECU5Rc-aI4cvHfENc4PuMTwNnHtFwFex11IFsdEns2ErZ05GXfn-sJVDMit8lfc5RSMTF9GHfHadBQ0OMfGA8MJ0H4DQ5t3LAl-Nx6y2';
//return dd($firebaseToken);
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        // dd($data);
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        // dd(curl_exec($ch));
        $response = curl_exec($ch);

      return  dd(curl_exec($ch));
    }


    public function whatsAppMessage(){


        $arr=json_encode(array(
            "phone"=>"963996222469",
            "body"=>"Hello Vishal"
        ));
        $url="https://api.chat-api.com/instance236201/sendMessage?token=99mjqfsogxm0fm6n";

//        $arr = json_encode(array(
//            "phone" => "91xxxxxxxxxx",
//            "body" => "https://upload.wikimedia.org/wikipedia/ru/3/33/NatureCover2001.jpg",
//            "filename" => "NatureCover2001.jpg"
//        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type:application/json',
            'Content-length:' . strlen($arr)
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;

    }
}
