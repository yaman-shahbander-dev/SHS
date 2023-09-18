<?php

namespace App\Http\Controllers;

use App\DataTables\PendingReviewDataTable;
use App\Models\reviews;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ReviewsRepositry;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class PendingReviewsController extends Controller
{
    /** @var  ReviewsRepositry */
    private $reviewRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(ReviewsRepositry $reviewRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->reviewRepository = $reviewRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param PendingReviewDataTable $reviewDataTable
     * @return Response
     */
    public function index(PendingReviewDataTable $reviewDataTable)
    {
        if(!auth()->user()->hasPermissionTo('pending.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $reviewDataTable->render('pending_reviews.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created Category in storage.
     *
     *     *
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
//        $category = $this->categoryRepository->findWithoutFail($id);
//
//        if (empty($category)) {
//            Flash::error('Category not found');
//
//            return redirect(route('categories.index'));
//        }
//
//        return view('categories.show')->with('category', $category);
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

        if(!auth()->user()->hasPermissionTo('pending.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $pendingReview = $this->reviewRepository->findWithoutFail($id);
       // return $subcategory;


        if (empty($pendingReview)) {
            Flash::error(trans('lang.review_not_found'));

            return redirect(route('reviews.index'));
        }


        return view('pending_reviews.fields')->with(['review'=> $pendingReview]);
    }

    /**
     * Update the specified reviews in storage.
     *
     * @param int $id
     *
     *
     * @return Response
     */
    public function approve(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('pending.approve')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

       // return dd($request->id);
       $review = reviews::find($request->id);


        if (empty($review)) {
            Flash::error(trans('lang.review_not_found'));
            return redirect(route('reviews.index'));        }

        try {
           // return dd($review);
           // $review->save();
         $review=$this->reviewRepository->update(['approved'=>1],$request->id);


        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        $vendor = User::find($review->vendor_id);

        $reviewer = User::find($review->client_id);

        //for send notification 
        $SERVER_API_KEY = 'AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $data = [
            "registration_ids" => array($vendor->fcm_token),
            "notification" => [
                "title"    => config('notification_lang.Notification_SP_reviews_title_' . $vendor->language),
                "body"     =>  $reviewer->name . ' '.config('notification_lang.Notification_SP_reviews_body_' . $vendor->language)
            ]
        ];

       // return $data;

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

        Flash::success(trans('lang.review_approved'));

        return redirect(route('reviews.index'));    }


        public function update($id,Request $request){

            if(!auth()->user()->hasPermissionTo('pending.update')){
                return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
            }

            $review = $this->reviewRepository->findWithoutFail($id);
//$review = reviews::find($id);

        if (empty($review)) {
            Flash::error(trans('lang.review_not_found'));
            return redirect(route('reviews.index'));        }

        try {
           // return dd($review);
           // $review->save();
           $input = $request->all();
           
           unset($input['homeowner_name']);
           unset($input['vendor_name']);

           $review=$this->reviewRepository->update($input,$id);
          // return dd($input);

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }


        Flash::success(trans('lang.update_operation'));

        return redirect(route('reviews.index'));  
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
        if(!auth()->user()->hasPermissionTo('pending.destroy')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }


        $review = $this->reviewRepository->findWithoutFail($id);

        if (empty($review)) {
            Flash::error(trans('lang.review_not_found'));

            return redirect(route('reviews.index'));
        }

        $this->reviewRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('reviews.index'));
    }



}
