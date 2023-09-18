<?php

namespace App\Http\Controllers;

use App\Models\TransferTransaction;
use Illuminate\Http\Request;
use App\DataTables\TransferTransactionDataTable;
use App\DataTables\TransactionHistoryDataTable;
use App\Repositories\CustomFieldRepository;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\TransferTransactionRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\User;
use App\Balance;
use DB;


class TransferTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /** @var  CategoryRepository */
    private $TransferTransactionRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(TransferTransactionRepository $TransferTransactionRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->TransferTransactionRepository = $TransferTransactionRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    public function index(TransferTransactionDataTable $transferTransactionDataTable)
    {
        if(!auth()->user()->hasPermissionTo('transfer.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $transferTransactionDataTable->render('transfer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermissionTo('transfer.create')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $hasCustomField = in_array($this->TransferTransactionRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->TransferTransactionRepository->model());
            $html = generateCustomField($customFields);
        }

        $users=User::all();

        if(count($users)!=0) {
            return view('transfer.create', ['users' => $users,'customFields'=> isset($html) ? $html : false]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('transfer.store')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

         $input = $request->all();
         $fromUser = User::find($input['fromUser']);
         $toUser = User::find($input['toUser']);

         $input['amount']  = strip_tags($input['amount']);

         if(preg_match('/[a-zA-Z]/', $input['amount'])) {
            Flash::Error(trans('lang.only_numbers'));
            return redirect(route('transfer.index'));
         }

         if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $input['amount'])) {
            Flash::Error(trans('lang.only_numbers'));
            return redirect(route('transfer.index'));
        }

         $fromUserBalanceID = $fromUser->balance_id;

         if ($fromUser->id == $toUser->id) {
            Flash::Error(trans('lang.transfer_same_user'));
            return redirect(route('transfer.index'));
         }

         if ($fromUserBalanceID == null) {
            Flash::Error(trans('lang.failed_balance') . '(' . $fromUser->name . ')' );
            return redirect(route('transfer.index'));
         }

         $toUserBalanceID   = $toUser->balance_id;

         if ($toUserBalanceID == null) {
            Flash::Error(trans('lang.failed_balance')  . '(' . $toUser->name . ')');
            return redirect(route('transfer.index'));
         }
  
         if (empty($input['amount'])) {
            Flash::Error(trans('lang.amount_value'));
            return redirect(route('transfer.index'));
         }

         if ($input['amount'] < 0) {
            Flash::Error(trans('lang.negative_amount'));
            return redirect(route('transfer.index'));
         }     

         $fromUserBalance   = Balance::find($fromUserBalanceID)->balance;
         $toUserBalance     = Balance::find($toUserBalanceID)->balance;

         $input['from_id'] = $input['fromUser'];
         $input['to_id']   = $input['toUser'];
         

         if ($fromUserBalance - $input['amount'] >= 0) {

            $subtractedAmount = $fromUserBalance - $input['amount'];
            $addAmountToUser  = $toUserBalance + $input['amount'];
            DB::table('balances')->where('id', $fromUserBalanceID)->update([ 'balance' => $subtractedAmount ]);
            DB::table('balances')->where('id', $toUserBalanceID)->update([ 'balance' => $addAmountToUser ]);
            
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->TransferTransactionRepository->model());

            try {
                $transfer = $this->TransferTransactionRepository->create($input);
                $transfer->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            } catch (ValidatorException $e) {
                Flash::error($e->getMessage());
            }
    
            Flash::success(trans('lang.success_transfer'));
            return redirect(route('transfer.index'));
         } else {
            Flash::success(trans('lang.failure_transfer'));
            return redirect(route('transfer.index'));
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransferTransaction  $transferTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(TransferTransaction $transferTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransferTransaction  $transferTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasPermissionTo('transfer.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $transfer = $this->TransferTransactionRepository->findWithoutFail($id);

        $users = User::all();

        if (empty($transfer)) {
            Flash::error(__('transfer not found', ['operator' => __('lang.category')]));

            return redirect(route('transfer.index'));
        }
        $customFieldsValues = $transfer->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->TransferTransactionRepository->model());
        $hasCustomField = in_array($this->TransferTransactionRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('transfer.edit')->with('transfer', $transfer)->with('users', $users)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransferTransaction  $transferTransaction
     * @return \Illuminate\Http\Response
     */
    public function update($id ,Request $request)
    {
        if(!auth()->user()->hasPermissionTo('transfer.update')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $transfer = $this->TransferTransactionRepository->findWithoutFail($id);

        if (empty($transfer)) {
            Flash::error(trans('lang.transfer_not_found'));
            return redirect(route('transfer.index'));
        }


         $input    = $request->all();
         $fromUser =  User::find($transfer['from_id']);
         $toUser   = User::find($transfer['to_id']);

         $fromUserBalanceID = $fromUser->balance_id;

         $input['amount']  = strip_tags($input['amount']);

         if(preg_match('/[a-zA-Z]/', $input['amount'])) {
            Flash::Error(trans('lang.only_numbers'));
            return redirect(route('transfer.index'));
         }

         if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $input['amount'])) {
            Flash::Error(trans('lang.only_numbers'));
            return redirect(route('transfer.index'));
        }

         if ($fromUser->id == $toUser->id) {
            Flash::Error(trans('lang.transfer_same_user'));
            return redirect(route('transfer.index'));
         }

         if ($fromUserBalanceID == null) {
            Flash::Error(trans('lang.failed_balance') . '(' . $fromUser->name . ')' );
            return redirect(route('transfer.index'));
         }

         $toUserBalanceID   = $toUser->balance_id;

         if ($toUserBalanceID == null) {
            Flash::Error(trans('lang.failed_balance')  . '(' . $toUser->name . ')');
            return redirect(route('transfer.index'));
         }

         if (empty($input['amount'])) {
            Flash::Error(trans('lang.amount_value'));
            return redirect(route('transfer.index'));
         }

         if ($input['amount'] < 0) {
            Flash::Error(trans('lang.negative_amount'));
            return redirect(route('transfer.index'));
         }

         

         $fromUserBalance   = Balance::find($fromUserBalanceID)->balance;
         $toUserBalance     = Balance::find($toUserBalanceID)->balance;

  

         $newtransferAmount = $input['amount'] - $transfer['amount']; // new amount - old amount

         
        if ($newtransferAmount >= 0) {

            if ($fromUserBalance - $newtransferAmount >= 0) {
                DB::table('balances')->where('id', $fromUserBalanceID)->update(['balance' => ($fromUserBalance - $newtransferAmount) ]);

                DB::table('balances')->where('id', $toUserBalanceID)->update(['balance' => ($toUserBalance + $newtransferAmount) ]);

            // end if Fromuser balance can be substracted 
            } else { 
            Flash::success(trans('lang.transfer_update_fail'));

            return redirect(route('transfer.index'));
            } // end else

            // end if new amount is bigger than the old one
        } else { // new amount is less than the old amount

            if ($toUserBalance - abs($newtransferAmount) >= 0) {

            DB::table('balances')->where('id', $fromUserBalanceID)->update(['balance' => ($fromUserBalance + abs($newtransferAmount)) ]);

                DB::table('balances')->where('id', $toUserBalanceID)->update(['balance' => ($toUserBalance - abs($newtransferAmount)) ]);

            // end if Touser balance can be substracted 
            } else { 
            Flash::success(trans('lang.transfer_update_fail'));

            return redirect(route('transfer.index'));
            } // end else
        } 
         
        try {

            $transfer = $this->TransferTransactionRepository->update($input, $id);

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.store_operation'));

        return redirect(route('transfer.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransferTransaction  $transferTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasPermissionTo('transfer.destroy')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $transfer = $this->TransferTransactionRepository->findWithoutFail($id);
        if (empty($transfer)) {
            Flash::error('transfer not found');

            return redirect(route('transfer.index'));
        }

        $this->TransferTransactionRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('transfer.index'));
    }

    public function transactionHistory($id,TransactionHistoryDataTable $transactionHistoryDataTable) {
 
         return $transactionHistoryDataTable->with('from_id',$id)->render('transfer.transactionHistory');
     }
}
