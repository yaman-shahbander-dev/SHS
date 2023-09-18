<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders =  Order::where('order_status_id','=',1)->where('active','=',1)->get();
        //\Log::info($orders);
        foreach($orders as $order)
        {
            if($order->timer >= 0)
            {
                if($order->timer == 0 && $order->confirmed_byRestaurant == 0)
                {
                    $order->active = 0;
                    $order->save();
                }
                else
                {
                    $order->timer = $order->timer - 1;
                    $order->save();
                }
                
            }
            \Log::info('cronjobs working');
        }
        
        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
        */
      
        $this->info('Timer:Cron Cummand Run successfully!');
    }
    
}
