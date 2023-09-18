<?php

namespace App\Http\Controllers;

use App\Duration;
use App\Models\Users;
use App\DataTables\durationDataTable;
use App\DataTables\DurationOfeersDataTable;
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

class durationOffersController extends Controller
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

    public function index(DurationOfeersDataTable $durationOffersDataTable)
    {
        if(!auth()->user()->hasPermissionTo('durationOffer.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $durationOffersDataTable->render('durationOffer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermissionTo('durationOffer.create')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $hasCustomField = in_array($this->durationRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->durationRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('durationOffer.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('durationOffer.store')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $input = $request->all();
        $input['duration'] = $request->duration_name;
        $input['duration_in_num'] = $request->duration_in_number;
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->durationRepository->model());
        try {
            $duration = $this->durationRepository->create($input);
            $duration->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(trans('lang.store_operation'));

        return redirect(route('durationOffer.index'));
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
        if(!auth()->user()->hasPermissionTo('durationOffer.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $duration = $this->durationRepository->findWithoutFail($id);


        if (empty($duration)) {
            Flash::error(trans('lang.duration_not_found'));

            return redirect(route('durationOffer.index'));
        }

        return view('durationOffer.edit')->with('duration', $duration)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Duration  $duration
     * @return \Illuminate\Http\Response
     */
    public function update($id, request $request)
    {
        if(!auth()->user()->hasPermissionTo('durationOffer.update')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $duration = $this->durationRepository->findWithoutFail($id);

        if (empty($duration)) {
            Flash::error(trans('lang.duration_not_found'));
            return redirect(route('country.index'));
        }
        $input = $request->all();
        $input['duration'] = $request->duration_name;
        $input['duration_in_num'] = $request->duration_in_number;

        // DB::table('countries')->where('id', $id)->update(['country_name' => $input['name']]);
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->durationRepository->model());
        try {
            $duration = $this->durationRepository->update($input, $id);
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $duration->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('durationOffer.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Duration  $duration
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasPermissionTo('durationOffer.destroy')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $duration = $this->durationRepository->findWithoutFail($id);

        if (empty($duration)) {
            Flash::error(trans('lang.duration_not_found'));

            return redirect(route('durationOffer.index'));
        }

        $this->durationRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('durationOffer.index'));
    }
}
