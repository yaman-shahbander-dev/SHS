<?php
/**
 * File name: CategoryAPIController.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Criteria\Categories\CategoriesOfCuisinesCriteria;
use App\Criteria\Categories\CategoriesOfRestaurantCriteria;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class CategoryAPIController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
        public function index(Request $request)
    {

            try {


        $categories = $this->categoryRepository->all(['id','name','description'])->makeHidden(['custom_fields','has_media','media'])->transform(function($q){
            $q->subCategory->transform(function($q){
                try{

                    $q['image']=$q->media[0]->getUrlAttribute();
                }
                catch (\Exception $e) {
                    $q['image']=url('images/image_default.png');
                }
                return $q->only('id','name','description','image');
            });
            try{

                $q['image']=$q->media[0]->getUrlAttribute();
            }
            catch (\Exception $e) {
                $q['image']=url('images/image_default.png');
            }
            return $q;
        });
        $response=$categories->toArray();

         return $this->sendResponse($response, 'Categories retrieved successfully');
            }
            catch (\Exception $e) {
                return $this->sendError('error save', 401);
            }

    }

    /**
     * Display the specified Category.
     * GET|HEAD /categories/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Category $category */
        if (!empty($this->categoryRepository)) {
            $category = $this->categoryRepository->findWithoutFail($id);
        }

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        return $this->sendResponse($category->toArray(), 'Category retrieved successfully');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

//        $input = $request->all();
//        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
//        try {
//            $category = $this->categoryRepository->create($input);
//            $category->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
//            if (isset($input['image']) && $input['image']) {
//                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
//                $mediaItem = $cacheUpload->getMedia('image')->first();
//                $mediaItem->copy($category, 'image');
//            }
//        } catch (ValidatorException $e) {
//            return $this->sendError($e->getMessage());
//        }
//
//        return $this->sendResponse($category->toArray(), __('lang.saved_successfully', ['operator' => __('lang.category')]));
   return $this->sendResponse([],'nothing');

    }

    

    /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $category = $this->categoryRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($category, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $category->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($category->toArray(), __('lang.updated_successfully', ['operator' => __('lang.category')]));

    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        $category = $this->categoryRepository->delete($id);

        return $this->sendResponse($category, __('lang.deleted_successfully', ['operator' => __('lang.category')]));
    }
}
