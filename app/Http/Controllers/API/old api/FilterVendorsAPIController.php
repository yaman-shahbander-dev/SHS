<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\specialOffers;
use App\Models\Homeowner_filter;
use App\Models\User;
class FilterVendorsAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $userSettings = Homeowner_filter::where('homeOwner_id', $user->id)->get(['homeOwner_id', 'vendor_filter']);

            return $this->sendResponse($userSettings->toArray(), 'Settings retrieved successfully');

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $userSettings = Homeowner_filter::where('homeOwner_id', $user->id)->get();

            if(count($userSettings) > 0) {
                return $this->sendResponse($userSettings->toArray(), 'Error setting is exist');
            }

            $userSettings = Homeowner_filter::create([
                'homeOwner_id'  => $user->id,
                'vendor_filter' => $request->filter_setting
            ]);
            return $this->sendResponse($userSettings->toArray(), 'Setting added successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $userSettings = Homeowner_filter::where('homeOwner_id', $user->id)->first(); 

            if(empty($userSettings)) {
                return $this->sendResponse($userSettings->toArray(), 'Error setting is not exist');
            } 
            
            $userSettings->vendor_filter = $request->filter_setting;

            if ($userSettings->save())
            return $this->sendResponse($userSettings->toArray(), 'setting updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
