<?php

namespace App\Http\Controllers;

use App\Delegate;
use Illuminate\Http\Request;
use App\Repositories\UploadRepository;
use App\Repositories\DelegateRepository;
use App\Repositories\CustomFieldRepository;
use App\DataTables\DelegateDataTable;
use Flash;
use App\Balance;

class DelegateController extends Controller
{
    /** @var  delegateRepository */
    private $delegateRepository;

    private $uploadRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    public function __construct(DelegateRepository $DelegateRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->delegateRepository = $DelegateRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DelegateDataTable $delegateDataTable)
    {
        if(!auth()->user()->hasPermissionTo('delegate.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $delegateDataTable->render('delegate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(!auth()->user()->hasPermissionTo('delegate.create')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $hasCustomField = in_array($this->delegateRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->delegateRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('delegate.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!auth()->user()->hasPermissionTo('delegate.store')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $input = $request->all();
        $input['name']=$input['name'];

        $input['phone']=$input['phone'];

        $checkDelegateName = Delegate::where('phone', $request->phone)->first();

        if (!empty($checkDelegateName)) { 
            Flash::error(trans('lang.salesman_exist'));
            return redirect()->route('sales_man.index');
        }

        try {
            $balance = new Balance();
            $request->balance == null ? $balance->balance = 0.0 : $balance->balance =$request->balance;
            $balance->save();
            $input['balance_id'] = $balance->id;
            unset($input['balance']);
            $salesMan = $this->delegateRepository->create($input);

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.store_operation'));

        return redirect(route('sales_man.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Delegate  $delegate
     * @return \Illuminate\Http\Response
     */
    public function show(Delegate $delegate)
    {
        $delegate = $this->delegateRepository->findWithoutFail($id);

        if (empty($delegate)) {
            Flash::error(trans('lang.delegate_not_found'));

            return redirect(route('delegate.index'));
        }

        return view('delegate.show')->with('delegate', $delegate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Delegate  $delegate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasPermissionTo('delegate.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $saleMan = $this->delegateRepository->findWithoutFail($id);


        if (empty($saleMan)) {
            Flash::error(trans('lang.delegate_not_found'));

            return redirect(route('sales_man.index'));
        }
        
        return view('delegate.edit')->with('saleMan', $saleMan)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Delegate  $delegate
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        if(!auth()->user()->hasPermissionTo('delegate.update')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $input = $request->all();
        $input['name']=$input['name'];

        $input['phone']=$input['phone'];

        $checkDelegateName = Delegate::where('phone', $request->phone)->first();

        
        if (!empty($checkDelegateName)) { 
            if ($id != $checkDelegateName->id) {
            Flash::error(trans('lang.salesman_exist'));
            return redirect()->route('sales_man.index');
            }
        }

        try {
            unset($input['balance']);
            $salesMan = $this->delegateRepository->update($input, $id);
            $salesMan->Balance->balance=$request->balance == null ? 0.0 : $request->balance;
            $salesMan->Balance->save();

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('sales_man.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Delegate  $delegate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasPermissionTo('delegate.destroy')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $salesman = $this->delegateRepository->findWithoutFail($id);

        if (empty($salesman)) {
            Flash::error(trans('lang.delegate_not_found'));

            return redirect(route('sales_man.index'));
        }

        if ($salesman->balance_id != null) {
            Balance::find($salesman->balance_id)->delete();
        }

        $this->delegateRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('sales_man.index'));
    }
}
