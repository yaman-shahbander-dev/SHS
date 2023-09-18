<?php

namespace App\Http\Controllers;

use App\Duration;
use App\Models\User;
use App\DataTables\durationDataTable;
use App\DataTables\UserDataTable;
use App\Repositories\UserRepository;
use App\Events\UserRoleChangedEvent;
use App\Http\Requests\UpdateCountryRequest;
use App\Repositories\DurationRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;

class DurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     /** @var  UserRepository */
     private $userRepository;

    /** @var  DurationRepository */
    private $durationRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(DurationRepository $durationRepo, UserRepository $userRepo ,CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->durationRepository = $durationRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }
    
    public function index(durationDataTable $durationRepository)
    {
        if(!auth()->user()->hasPermissionTo('duration.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $durationRepository->render('duration.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermissionTo('duration.create')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $hasCustomField = in_array($this->durationRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->durationRepository->model());
            $html = generateCustomField($customFields);
        }

        $vendors = User::with('roles')->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')->where('role_id',3)->get();

        $durations = Duration::all();

        return view('duration.create')->with('vendors', $vendors)->with('durations', $durations)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!auth()->user()->hasPermissionTo('duration.store')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $input = [];

        $input['id']              = $request->vendornameselect;

        $input['duration_id']     = $request->duration;

        $duration_in_num = DB::table('durations')->select('durations.duration_in_num')->where('durations.id', '=', $request->duration)->get();

        $duration_in_num = $duration_in_num[0]->duration_in_num;

        $input['start_date'] = date('Y-m-d'); 

        $input['expire'] = date('Y-m-d', strtotime('+'.$duration_in_num.' years'));

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        try {
            $duration = $this->userRepository->update($input, $request->vendornameselect);
            $duration->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.store_operation'));

        return redirect(route('vendorRegistration.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Duration  $duration
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Duration  $duration
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasPermissionTo('duration.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $duration = $this->userRepository->findWithoutFail($id);


        if (empty($duration)) {
            Flash::error(trans('lang.SP_not_found'));

            return redirect(route('duration.index'));
        }

        $durations = Duration::all();

        return view('duration.edit')->with('duration', $duration)->with('durations', $durations)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Duration  $duration
     * @return \Illuminate\Http\Response
     */
    public function update($id, request $request)
    {

        if(!auth()->user()->hasPermissionTo('duration.update')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }


        $duration = $this->userRepository->findWithoutFail($id);

        if (empty($duration)) {
            Flash::error(trans('lang.SP_not_found'));
            return redirect(route('vendorRegistration.index'));
        }

        $user = User::find($duration->id);

        if ($user->duration_id == $request->duration){ 

            $input['start_date'] = $request->start_date; 

            $input['expire'] = $request->expire;

        } else {
            $duration_in_num = DB::table('durations')->select('durations.duration_in_num')->where('durations.id', '=', $request->duration)->get();

            $duration_in_num = $duration_in_num[0]->duration_in_num;

            $input['duration_id']= $request->duration;

            $input['start_date'] = date('Y-m-d'); 

            $input['expire'] = date('Y-m-d', strtotime('+'.$duration_in_num.' years'));
        }
        

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        try {
            $duration = $this->userRepository->update($input, $id);
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $duration->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('vendorRegistration.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Duration  $duration
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
