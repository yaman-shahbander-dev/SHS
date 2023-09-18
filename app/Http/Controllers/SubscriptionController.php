<?php

namespace App\Http\Controllers;

use App\Subscription;

use App\DataTables\SubscriptionDataTable;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;
use App\Http\Requests\UpdateSubscriptionRequest;


class SubscriptionController extends Controller
{
     /** @var  VendorSuggRepository */

     private $subscriptionRepository;
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    public function __construct(SubscriptionRepository $subscriptionRepo,
                                CustomFieldRepository $customFieldRepo)
    {
        parent::__construct();
        $this->subscriptionRepository = $subscriptionRepo;
        $this->customFieldRepository = $customFieldRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubscriptionDataTable $subscriptionDataTable)
    {
        return $subscriptionDataTable->render('subscription.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hasCustomField = in_array($this->subscriptionRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subscriptionRepository->model());
            $html = generateCustomField($customFields);
        }

        return view('subscription.create')
            ->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subscriptionRepository->model());

        $input['type']=$input['type'];
        $input['duration']=$input['duration'];
        $input['discount']=$input['discount'];

        try {
            $subscription = $this->subscriptionRepository->create($input);
            $subscription->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));


            // event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success('saved successfully.');

        return redirect(route('subscription.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($vendor)) {
            Flash::error('Vendor not found');

            return redirect(route('subscription.index'));
        }

        return view('subscription.show')->with('vendor', $vendor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $subscription = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($subscription)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('subscription.index'));
        }
        $customFieldsValues = $subscription->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subscriptionRepository->model());
        $hasCustomField = in_array($this->subscriptionRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('subscription.edit')->with('subscription', $subscription)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateSubscriptionRequest $request)
    {

        $subscription = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($subscription)) {
            Flash::error('subscription not found');
            return redirect(route('subscription.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subscriptionRepository->model());
        try {
            $subscription = $this->subscriptionRepository->update($input, $id);


            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $subscription->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.vendor')]));

        return redirect(route('subscription.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
