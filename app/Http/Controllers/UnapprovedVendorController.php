<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VendorRepository;
use Flash;
use App\DataTables\UnapprovedVendorDataTable;
use App\Models\User;
use App\Balance;
class UnapprovedVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    private $vendorRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct(VendorRepository $vendorRepo)
    {
    parent::__construct();
    $this->vendorRepository = $vendorRepo;
    }

    public function index(UnapprovedVendorDataTable $vendorDataTable)
    {
        if(!auth()->user()->hasPermissionTo('unapprovedServiceProvider.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $vendorDataTable->render('unapprovedVendor.index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "sss";
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
    public function destroy($id)
        {
            if(!auth()->user()->hasPermissionTo('unapprovedServiceProvider.destroy')){
                return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
            }

        $user = $this->vendorRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::success(trans('lang.user_not_found'));
            return redirect(route('unapprovedServiceProvider.index'));
        }



        if ($user->balance_id != null) {
            Balance::find($user->balance_id)->delete();
        }

        try{ unlink(public_path('storage/Avatar').'/'.$user->avatar);}
        catch (\Exception $e) {}

        $this->vendorRepository->delete($id);

        //for send notification
        $SERVER_API_KEY = 'AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $data = [
            "registration_ids" => array($user->fcm_token),
            "notification" => [
                "title"    => config('notification_lang.Notification_title_unapproved_' . $user->language),
                "body"     => config('notification_lang.Notification_body_unapproved_' . $user->language)
            ]
        ];

        $dataString = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
         //return dd(curl_exec($ch));
        $response = curl_exec($ch);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('unapprovedServiceProvider.index'));
    }

    public function approved($id,Request $request)
    {

        if(!auth()->user()->hasPermissionTo('unapprovedServiceProvider.approve')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        try {

            $user = User::find($id);
           $user->approved_vendor = 1;
            if($user->save()){



             //for send notification
        $SERVER_API_KEY = 'AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $data = [
            "registration_ids" => array($user->fcm_token),
            "notification" => [
                "title"    => config('notification_lang.Notification_title_approved_' . $user->language),
                "body"     => config('notification_lang.Notification_body_approved_' . $user->language)
            ]
        ];

        $dataString = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
         //return dd(curl_exec($ch));
        $response = curl_exec($ch);
        Flash::success(trans('lang.store_operation'));

        return redirect(route('unapprovedServiceProvider.index'));
    }
    else{
        Flash::error($e->getMessage());
        return redirect(route('unapprovedServiceProvider.index'));
    }


        } catch (ValidatorException $e) {
        Flash::error($e->getMessage());
        return redirect(route('unapprovedServiceProvider.index'));
   }
 }

}
