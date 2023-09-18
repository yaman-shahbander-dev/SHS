<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ContactAPIController extends Controller
{
    public function contactVendor(Request $request) {
        try {
            if($request->header('devicetoken')) {

                $user = User::where('device_token', $request->header('devicetoken'))->first();
                
                if(empty($user)) { return $this->sendError('User not found', 401); }

                $user->vendorContacts()->attach($request->vendor_id);

                return $this->sendResponse([], 'Vendor has been contacted');
            }
      } catch(\Exception $e) {
          return $this->sendError("Error", 401);
      }
    }
}
