<?php

namespace App\Http\Controllers;

use App\DataTables\VendorSuggestedDataTable;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\VendorSuggRepository;
use App\vendors_suggested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;
use App\Country;
use App\Delegate;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Repositories\UserRepository;
use App\Balance;

class VendorsSuggestedController extends Controller
{
    /** @var  VendorSuggRepository */

    private $vendorSugRepository;
    private $roleRepository;

    private $userRepository;
    private $uploadRepository;


    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    public function __construct(
        VendorSuggRepository $vendorSugRepo,
        RoleRepository $roleRepo,
        UserRepository $userRepo,
        CustomFieldRepository $customFieldRepo,
        UploadRepository $uploadRepo
    ) {
        parent::__construct();
        $this->vendorSugRepository = $vendorSugRepo;
        $this->roleRepository = $roleRepo;
        $this->userRepository = $userRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }
    /**
     * Display a listing of the Vendors.
     *
     * @param VendorSuggestedDataTable $vendorsugDataTable
     * @return Response
     */
    public function index(VendorSuggestedDataTable $vendorsugDataTable)
    {
        if (!auth()->user()->hasPermissionTo('suggestedvendor.index')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $vendorsugDataTable->render('settings.vendors_suggested.index');
    }
    /**
     * Show the form for creating a new Vendor.
     *
     * @return Response
     */
    public function create()
    {

        if (!auth()->user()->hasPermissionTo('suggestedvendor.create')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $hasCustomField = in_array($this->vendorSugRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorSugRepository->model());
            $html = generateCustomField($customFields);
        }

        return view('settings.vendors_suggested.create')
            ->with("customFields", isset($html) ? $html : false);
    }

    public function store(Request $request)
    {

        if (!auth()->user()->hasPermissionTo('suggestedvendor.store')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorSugRepository->model());
        $input['user_id'] = auth()->user()->id;
        try {

            $checkEmail = vendors_suggested::where('email', '=', $input['email'])->first();

            if ($checkEmail != null) {
                Flash::error(trans('validation.email'));

                return redirect(route('vendor.create'));
            }

            $checkPhone = vendors_suggested::where('phone', '=', $input['phone'])->first();

            if ($checkPhone != null) {
                Flash::error(trans('validation.phone'));

                return redirect(route('vendor.create'));
            }

            $vendor = $this->vendorSugRepository->create($input);

            $vendor->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(trans('lang.store_operation'));
        return redirect(route('vendor.index'));
    }

    /**
     * Display the specified vendor.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $vendor = $this->vendorSugRepository->findWithoutFail($id);

        if (empty($vendor)) {
            Flash::error(trans('lang.SP_not_found'));

            return redirect(route('vendor.index'));
        }
        $countries = Country::all();
        $users = User::all();
        $sugg_user = User::find($vendor->user_id);
        //        $cities=City::all();
        $role = $this->roleRepository->pluck('name', 'name');
        // $rolesSelected = $user->getRoleNames()->toArray();

        $rolesSelected = [];
        return view('settings.vendors_suggested.show')
            ->with('vendor', $vendor)
            ->with('countries', $countries)
            ->with('role', $role)
            ->with('users', $users)
            ->with('sugg_user', $sugg_user)
            ->with('rolesSelected', $rolesSelected);
    }
    /**
     * Show the form for editing the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo('suggestedvendor.edit')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $vendor = $this->vendorSugRepository->findWithoutFail($id);


        if (empty($vendor)) {
            Flash::error(trans('lang.SP_not_found'));

            return redirect(route('vendor.index'));
        }
        $customFieldsValues = $vendor->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorSugRepository->model());
        $hasCustomField = in_array($this->vendorSugRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('settings.vendors_suggested.edit')->with('vendor', $vendor)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('suggestedvendor.update')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $vendor = $this->vendorSugRepository->findWithoutFail($id);

        if (empty($vendor)) {
            Flash::error(trans('lang.SP_not_found'));
            return redirect(route('vendor.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorSugRepository->model());
        try {
            $vendor = $this->vendorSugRepository->update($input, $id);


            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $vendor->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('vendor.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('suggestedvendor.destroy')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $vendor = $this->vendorSugRepository->findWithoutFail($id);

        if (empty($vendor)) {
            Flash::error(trans('lang.SP_not_found'));

            return redirect(route('vendor.index'));
        }

        $this->vendorSugRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('vendor.index'));
    }

    public function store_vendors_suggested($id, Request $request)
    {

        if (!auth()->user()->hasPermissionTo('suggestedvendor.save')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        try {

            if ($request->city == "0") {
                Flash::warning(trans('lang.select_country_city'));
                return redirect()->back();
            }
            $input = $request->all();

            $input['password'] = Hash::make($input['password']);
            while (true) {
                $payment_id = '#' . rand(1000, 9999) . rand(1000, 9999);
                if (!(User::where('payment_id', $payment_id)->exists())) {
                    break;
                } else continue;
            }
            $input['approved_vendor'] = 1;
            $input['language'] = $request->input('language') == null ? '' : $request->input('language', '');
            $input['phone'] = $request->input('phone') == null ? '' : $request->input('phone', '');
            $input['payment_id'] = $payment_id;
            $balance = new Balance();
            $balance->balance = 0.0;
            $balance->save();
            $input['balance_id'] = $balance->id;
            $input['is_verified'] = 1;
            $input['city_id'] = $request->city;
            $token = openssl_random_pseudo_bytes(16);
            $user = $this->userRepository->create($input);

            //Convert the binary data into hexadecimal representation.
            $token = bin2hex($user->id . $token);
            $input['device_token'] = $token;
            $user = $this->userRepository->update($input, $user->id);

            $user->assignRole('vendor');

            try {
                if ($request->file('avatar')) {
                    $imageName = uniqid() . $request->file('avatar')->getClientOriginalName();

                    $request->file('avatar')->move(public_path('storage/Avatar'), $imageName);

                    $user->avatar = $imageName;
                    $user->save();
                }

                vendors_suggested::find($id)->delete();
            } catch (ValidatorException $e) {
                Flash::error($e->getMessage());
            }
            Flash::success(trans('lang.store_operation'));

            return redirect(route('vendors.index'));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
            return redirect(route('vendors.index'));
        }
    }
}
