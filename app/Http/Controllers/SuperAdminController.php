<?php

namespace App\Http\Controllers;

use App\Country;
use App\DataTables\RatingDataTable;
use App\DataTables\UserDataTable;
use App\DataTables\AdminUserDataTable;
use App\DataTables\SuperAdminDataTable;

use App\DataTables\VendorDataTable;
use App\DataTables\SubCategoriesVendorDataTable;

use App\Events\UserRoleChangedEvent;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\PermissionRepository;

use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use App\Repositories\VendorRepository;
use Flash;
use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\subCategory;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\Route;
use App\Models\GmapLocation;
use DB;
use App\Jobs\SendVerificationEmail;
use App\Models\User;
use App\Balance;

class SuperAdminController extends Controller
{
    /** @var  UserRepository */
    private $userRepository;

    /** @var  VendorRepository */
    private $vendorRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    private $uploadRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;


    public function __construct(
        VendorRepository $vendorRepo,
        UserRepository $userRepo,
        RoleRepository $roleRepo,
        UploadRepository $uploadRepo,
        CustomFieldRepository $customFieldRepo
    ) {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->vendorRepository = $vendorRepo;
        $this->roleRepository = $roleRepo;
        $this->uploadRepository = $uploadRepo;
        $this->customFieldRepository = $customFieldRepo;
    }


    public function index(SuperAdminDataTable $userDataTable)
    {
        if (!auth()->user()->hasPermissionTo('superadmins.index')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $userDataTable->render('settings.superadmins.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasPermissionTo('superadmins.create')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $countries = Country::all();
        //        $cities=City::all();
        $role = $this->roleRepository->pluck('name', 'name');

        $rolesSelected = [];
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $html = generateCustomField($customFields);
        }
        $style = "";
        return view('settings.superadmins.create')
            ->with("role", $role)
            ->with("customFields", isset($html) ? $html : false)
            ->with("rolesSelected", $rolesSelected)
            ->with("style", $style)
            ->with("countries", $countries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('superadmins.store')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if ($request->city == "0") {
            Flash::warning('please select country and city ');
            return redirect()->back();
        }

        if ($request->input('email') == null && $request->input('phone') == null) {
            Flash::error(trans('lang.require_email_phone'));
            return redirect()->back();
        }

        $input = $request->all();

        $input['user_id'] = Auth()->user()->id;
        $input['password'] = Hash::make($input['password']);

        while (true) {
            $payment_id = '#' . rand(1000, 9999) . rand(1000, 9999);
            if (!(User::where('payment_id', $payment_id)->exists())) {
                break;
            } else continue;
        }

        $input['language'] = $request->input('language') == null ? '' : $request->input('language', '');
        $input['phone'] = $request->input('phone') == null ? '' : $request->input('phone', '');
        $input['payment_id'] = $payment_id;
        $balance = new Balance();
        $balance->balance = 0.0;
        $balance->save();
        $input['balance_id'] = $balance->id;
        $input['is_verified'] = 0;
        $input['city_id'] = $request->city;
        $token = openssl_random_pseudo_bytes(16);
        $user = $this->vendorRepository->create($input);

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($user->id . $token);
        $input['device_token'] = $token;
        $user = $this->vendorRepository->update($input, $user->id);

        $user->assignRole('superadmin');
        $user->assignRole($request->roles);


        try {

            if ($request->file('avatar')) {

                $imageName = uniqid() . $request->file('avatar')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('avatar')->move(public_path('storage/Avatar'), $imageName);

                $user->avatar = $imageName;
                $user->save();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.store_operation'));

        return redirect(route('superAdminsBoard.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request, SuperAdminDataTable $superAdminUserDataTable)
    {
        if (!auth()->user()->hasPermissionTo('superadmins.profile')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $countries = Country::all();

        $user = $this->userRepository->findWithoutFail($request->id);
        unset($user->password);
        $customFields = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        //dd($customFieldsValues);
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFields = generateCustomField($customFields, $customFieldsValues);
        }
        if (!empty($user->cities->id)) {
            $cities = City::where('country_id', $user->cities->country_id)->get();
        } else
            $cities = [];
        //  return dd($user->subcategories);
        $user->rating = getRating($user);

        $userCoordinates =  GmapLocation::where('user_id', $request->id)->first();

        if (!empty($userCoordinates)) {
            Mapper::map(
                $userCoordinates->latitude,
                $userCoordinates->longitude,
                [
                    'zoom'      => 8,
                    'draggable' => false,
                    'marker'    => true,
                    'markers' => ['title' => 'My Location', 'animation' => 'BOUNCE']
                ]
            );

            // document.getElementById("gmap").style.
            $style = 'style="width: 100%; height: 300px"';
        } else {
            $style = "";
        }

        return $dataTable = $superAdminUserDataTable->render('settings.superadmins.profile', compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues', 'countries', 'cities', 'style']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo('superadmins.edit')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if ($id == 1) {
            Flash::error(trans('lang.Permission_denied'));
            return redirect(route('adminsBoard.index'));
        }

        $countries = Country::all();

        $user = $this->userRepository->findWithoutFail($id);
        unset($user->password);
        $html = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        if (empty($user)) {
            Flash::error(trans('lang.superadmin_not_found'));

            return redirect(route('superAdminsBoard.index'));
        }
        if (!empty($user->cities->id)) {
            $cities = City::where('country_id', $user->cities->country_id)->get();
        } else
            $cities = [];
        $style = "";
        return view('settings.superadmins.edit')
            ->with('user', $user)->with("role", $role)
            ->with("rolesSelected", $rolesSelected)
            ->with("customFields", $html)
            ->with("style", $style)
            ->with("countries", $countries)
            ->with("cities", $cities);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        if (!auth()->user()->hasPermissionTo('superadmins.update')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        // dd($request->input());
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('superAdminsBoard.profile'));
        }
        if ($id == 1) {
            Flash::error(trans('lang.Permission_denied'));
            return redirect(route('superAdminsBoard.profile'));
        }
        if ($request->city == "0") {
            Flash::warning('please select country and city ');
            return redirect()->back();
        }

        if ($request->input('email') == null && $request->input('phone') == null) {
            Flash::error(trans('lang.require_email_phone'));
            return redirect()->back();
        }

        $input = $request->all();

        $input['user_id'] = Auth()->user()->id;
        $input['password'] = Hash::make($input['password']);

        $input['language'] = $request->input('language') == null ? '' : $request->input('language', '');
        $input['phone'] = $request->input('phone') == null ? '' : $request->input('phone', '');

        $input['city_id'] = $request->city;

        unset($input['email']);

        unset($input['phone']);

        $user = $this->userRepository->update($input, $id);

        if ($user->email != $request->email) {

            $checkEmail = User::where('email', '=', $request->email)->first();

            if ($checkEmail != null) {
                Flash::error(trans('validation.email'));

                return redirect(route('superAdminsBoard.edit', [$user->id]));
            } else {
                $user->email = $request->email;
            }
        }

        if ($user->phone != $request->phone) {

            $checkPhone = User::where('phone', '=', $request->phone)->first();

            if ($checkPhone != null) {
                Flash::error(trans('validation.phone'));

                return redirect(route('superAdminsBoard.edit', [$user->id]));
            } else {
                $user->phone = $request->phone;
            }
        }

        $user->save();

        DB::table('model_has_roles')->where('model_id', $user->id)->delete();

        $user->assignRole($request->roles);

        try {

            if ($request->file('avatar')) {

                $imageName = uniqid() . $request->file('avatar')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('avatar')->move(public_path('storage/Avatar'), $imageName);

                try {
                    unlink(public_path('storage/Avatar') . '/' . $user->avatar);
                } catch (\Exception $e) {
                }

                $user->avatar = $imageName;
                $user->save();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('superAdminsBoard.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('superadmins.destroy')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('adminsBoard.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error(trans('lang.superadmin_not_found'));

            return redirect(route('superAdminsBoard.index'));
        }

        if ($user->balance_id != null) {
            Balance::find($user->balance_id)->delete();
        }

        try {
            unlink(public_path('storage/Avatar') . '/' . $user->avatar);
        } catch (\Exception $e) {
        }

        $this->userRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('superAdminsBoard.index'));
    }
}
