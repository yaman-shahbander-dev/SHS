<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ViewsAPIController extends Controller
{
    public function viewVendor(Request $request) {
        try {
            if($request->header('devicetoken')) {

                $user = User::where('device_token', $request->header('devicetoken'))->first();
                
                if(empty($user)) { return $this->sendError('User not found', 401); }

                $user->vendorViews()->attach($request->vendor_id);

                return $this->sendResponse([], 'Vendor has been viewed');
            }
      } catch(\Exception $e) {
          return $this->sendError("Error", 401);
      }
    }
}
