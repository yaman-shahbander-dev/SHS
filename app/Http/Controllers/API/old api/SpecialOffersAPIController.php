<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\specialOffers;
use App\Models\User;
class SpecialOffersAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

        if($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $vendoroffers    =  specialOffers::where('user_id', $user->id)->get(['id', 'description', 'title', 'image']);

            $user1 = User::where('device_token', $request->header('devicetoken'))->first();

            $sub= $user->subcategories->transform(function($q) {
                return  $q->id;
             });

            $user1 = $user1->subcategories->transform(function($q) use($sub){
                $arr = $q->categories->subCategory->transform(function($s) use($sub){
                    if (in_array($s->id, $sub->toArray()))
                    return $s->only('id', 'name') ;

                });



                $q['id']            = $q->categories->id;
                $q['name']          = $q->categories->name;

                $q['subcategories'] = $arr->filter(function ($value) {
                    return $value != null;
                });;

                return  $q->only('id', 'name', 'subcategories');
            })->unique('id');

            $response = [];
            $response[] = [
                'categories'  =>  $user1
            ];
            foreach($vendoroffers as $info) {
                $response['offers'][] = [
                    'id'          => $info->id,
                    'description' => $info->description,
                    'title'       => $info->title,
                    'image'       => url('storage/specialOffersPic/' . $info->image)
                ];
            }
            return $this->sendResponse($response, 'Offers retrieved successfully');
        }
      } catch (\Exception $e) {
        return $this->sendError("Something is wrong", 401); }
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

        try {

        if($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();

                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $vendor_specialOffer = new specialOffers();
                $vendor_specialOffer->user_id = $user->id;
                $vendor_specialOffer->description = $request->description;
                $vendor_specialOffer->title = $request->title;
                $vendor_specialOffer->subcategory_id = $request->subcategory_id;
                $vendor_specialOffer->image = "default.png";
                $response = [];


                if ($vendor_specialOffer->save()){

                    $response = [
                        'user_id'          => $vendor_specialOffer->user_id,
                        'description'      => $vendor_specialOffer->description,
                        'title'            => $vendor_specialOffer->title,
                        'subcategory_id'   => $vendor_specialOffer->subcategory_id,
                        'offer_id'         => $vendor_specialOffer->id,
                    ];

                    if (!empty ($request->file('image'))) {

                    $imageName = uniqid() . $request->file('image')->getClientOriginalName();

                    $request->file('image')->move(public_path('storage/specialOffersPic'), $imageName);

                    $vendor_specialOffer->update(['image' => $imageName]);

                    $response['image'] = asset('storage/specialOffersPic') . '/' .$imageName;

                    return $this->sendResponse($response, 'Offers Saved successfully');

                    } else{
                        $response['image'] = asset('storage/specialOffersPic') . '/default.jpg' ;

                    return $this->sendResponse($response, 'Offers Saved successfully with default image');
                    }
                }  else
                return $this->sendResponse([], 'Error');
       }
    } catch (\Exception $e) {
        return $this->sendError("Something is wrong", 401); }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            if($request->header('devicetoken')) {

                $user = User::where('device_token', $request->header('devicetoken'))->first();

                    if (empty($user)) {
                        return $this->sendError('User not found', 401);
                    }

                    specialOffers::find($request->offer_id)->delete();

                    return $this->sendResponse([], 'Offer deleted successfully!');
            } else return $this->sendError("Error!", 401);
        } catch (\Exception $e) {
            return $this->sendError("Something is wrong", 401); }
    }
}
