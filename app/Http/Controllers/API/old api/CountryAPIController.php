<?php

namespace App\Http\Controllers\api;

use App\Country;
use App\City;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CountryRepository;
use App\Repositories\customFieldRepository;
use function foo\func;

class CountryAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /** @var  CountryRepository */
    private $countryRepository;

    public function __construct(CountryRepository $countryRepo)
    {
        $this->countryRepository = $countryRepo;
    }
    public function index(Request $request)
    {

            try {

        $countries = Country::with('City')->get(["id", "country_name"])->transform(function($q){
            $q->City->makeHidden(['created_at','updated_at','name_en','country_id']);
            return $q;
        });

        return $this->sendResponse($countries->toArray(), 'Countries retrieved successfully');
            }
            catch (\Exception $e) {
                return $this->sendError('error', 401);
            }

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
        // $input = $request->all();

        // $country = $this->countryRepository->create($input);
        // return $this->sendResponse($country->toArray(), __('lang.saved_successfully', ['operator' => __('lang.category')]));


        // // $country = new Country();
        // // $country->country_name = $input['country_name'];
        // // $country->save();
        //  //$country = $this->countryRepository->create($input);
        // // return $this->sendResponse($country->toArray(), __('lang.saved_successfully', ['operator' => __('lang.category')]));
        // $country = $this->countryRepository->create($input);
        // return $this->sendResponse($country->toArray(), __('lang.saved_successfully', ['operator' => __('lang.category')]));


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // if (!empty($this->countryRepository)) {
        //     $country = $this->countryRepository->findWithoutFail($id);
        // }

        // if (empty($country)) {
        //     return $this->sendError('Country not found');
        // }

        // return $this->sendResponse($country->toArray(), 'Country retrieved successfully');
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
    public function update($id, Request $request)
    {
        // $country = $this->countryRepository->findWithoutFail($id);

        // if (empty($country)) {
        //     return $this->sendError('Country not found');
        // }

        // $input = $request->all();
        // $country = $this->countryRepository->update($input, $id);

        // return $this->sendResponse($country->toArray(), __('lang.updated_successfully', ['operator' => __('lang.category')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $country = $this->countryRepository->findWithoutFail($id);

        // if (empty($country)) {
        //     return $this->sendError('Country not found');
        // }

        // $country = $this->countryRepository->delete($id);

        // return $this->sendResponse($country, __('lang.deleted_successfully', ['operator' => __('lang.category')]));
    }
}

