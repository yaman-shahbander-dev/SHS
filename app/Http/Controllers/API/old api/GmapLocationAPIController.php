<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GmapLocation;
use App\Models\User;

class GmapLocationAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $checkUserCoordinates = GmapLocation::where('user_id', $user->id)->first();
                if (!empty($checkUserCoordinates)) {
                    return $this->sendResponse([], 'Coordinates are exist');
                }

                $coordinates = new GmapLocation();
                $coordinates->user_id = $user->id;
                $coordinates->latitude = $request->latitude;
                $coordinates->longitude = $request->longitude;
                if ($coordinates->save()) {
                    $coordinates->makeHidden(['user_id', 'updated_at', 'created_at', 'id']);
                    return $this->sendResponse($coordinates->toArray(), 'Coordinates Saved successfully');
                } else {
                    return $this->sendResponse([], 'Coordinates failed to save');
                }
            } catch (\Exception $e) {
                return $this->sendError('something was wrong', 401);

            }
        }
        else
            return $this->sendError('You dont have permission', 401);
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

            $updateUserCoordinates =  GmapLocation::where('user_id', $user->id)->first();

            $updateUserCoordinates->latitude = $request->latitude;
            $updateUserCoordinates->longitude = $request->longitude;

            if ($updateUserCoordinates->save()){
                $updateUserCoordinates->makeHidden(['user_id', 'updated_at', 'created_at', 'id', 'icon']);
                return $this->sendResponse($updateUserCoordinates->toArray(), 'Coordinates Updated successfully');
            } else {
                return $this->sendResponse([], 'Coordinates failed to updated');
            }
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

    public function VendorMapDetails(Request $request) {
        $id = $request->id; //subcategory id

        $userID = $request->user_id; // user id

        $user = User::find($userID);

        $userLatitude = $user->coordinates->latitude;

        $userLongitude = $user->coordinates->longitude;

        $respone = [];

        $vendors = User::whereHas('subcategories', function($query) use ($id) {
            $query->where('subcategory_id', $id);
        })->get();

        $i = 0;
        foreach($vendors as $vendor) {
            $respone[$i] = [
                'id'            => $vendor->id,
                'name'          => $vendor->name,
                'email'         => $vendor->email,
                'rating'        => getRating($vendor),
                'description'   => $vendor->description,
                'avatar'        => $vendor->getFirstMediaUrl('avatar','icon'),
                'coordinates'   => [
                    "latitude"  =>
                    $vendor->coordinates ? $vendor->coordinates->latitude : 0,
                    "longitude" =>
                    $vendor->coordinates ? $vendor->coordinates->longitude : 0
                ],
                'distance'      => $vendor->coordinates ? distance(floatval($userLatitude), floatval($userLongitude), floatval($vendor->coordinates->latitude), floatval($vendor->coordinates->longitude)) : 'No coordinates provided for the current vendor'
            ];
            $i++;
        }
        return $this->sendResponse($respone, 'vendors retrieved successfully');

    }
}
