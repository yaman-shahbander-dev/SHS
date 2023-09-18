<?php
/**
 * File name: NotificationAPIController.php
 * Last modified: 2020.05.07 at 10:41:01
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;

/**
 * Class NotificationController
 * @package App\Http\Controllers\API
 */
class NotificationAPIController extends Controller
{
    /** @var  NotificationRepository */
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepo)
    {
        $this->notificationRepository = $notificationRepo;
    }

    /**
     * Display a listing of the Notification.
     * GET|HEAD /notifications
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->notificationRepository->pushCriteria(new RequestCriteria($request));
            $this->notificationRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $notifications = $this->notificationRepository->all();
        foreach($notifications as $notification)
        {
            $order_id = json_decode($notification->data)->order_id;
            $notification->order_id = $order_id;
            $notification->restaurant_name = $this->get_restaurant_name($order_id);
             
            
        }
        $notifications[] = array('message' => 'Notifications retrieved successfully');
         $notifications[] = array('success' => true);
         //return $notifications;
         /*return [
            'success' => true,
            'data'    => json_decode($notifications),
            'message' => 'Notifications retrieved successfully',
        ];*/
        return response()->json($notifications->toArray(), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
        //return $this->sendResponse($notifications->toArray(), 'Notifications retrieved successfully');
       
    }
    
    public function get_restaurant_name($order_id)
    {
        $restaurant = DB::table('food_orders')
        
        ->where('order_id','=',$order_id)
        ->leftjoin('foods', 'food_orders.food_id', '=', 'foods.id')
        ->leftjoin('restaurants', 'foods.restaurant_id', '=', 'restaurants.id')
        ->select('restaurants.name as name')
        ->first();
        $restaurant = $restaurant->name;
        return $restaurant;
        exit;
        return json_encode($restaurant, JSON_UNESCAPED_UNICODE);
        return json_encode($restaurant);
        return response()->json($restaurant, 200, [], JSON_UNESCAPED_UNICODE);
        //json_encode($multibyte_string, JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display the specified Notification.
     * GET|HEAD /notifications/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Notification $notification */
        if (!empty($this->notificationRepository)) {
            $notification = $this->notificationRepository->findWithoutFail($id);
        }

        if (empty($notification)) {
            return $this->sendError('Notification not found');
        }

        return $this->sendResponse($notification->toArray(), 'Notification retrieved successfully');
    }

    /**
     * Update the specified Notification in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $notification = $this->notificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            return $this->sendError('Notification not found');
        }
        $input = $request->all();

        if (isset($input['read_at'])) {
            if ($input['read_at'] == true) {
                $input['read_at'] = Carbon::now();
            } else {
                unset($input['read_at']);
            }
        }
        try {
            $notification = $this->notificationRepository->update($input, $id);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($notification->toArray(), __('lang.saved_successfully', ['operator' => __('lang.notification')]));
    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $notification = $this->notificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            return $this->sendError('Notification not found');
        }

        $this->notificationRepository->delete($id);

        return $this->sendResponse($notification, __('lang.deleted_successfully', ['operator' => __('lang.notification')]));

    }
}
