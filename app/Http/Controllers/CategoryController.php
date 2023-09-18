<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Repositories\CustomFieldRepository;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class CategoryController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(CategoryRepository $categoryRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(CategoryDataTable $categoryDataTable)
    {
        if(!auth()->user()->hasPermissionTo('categories.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $categoryDataTable->render('categories.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermissionTo('categories.create')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $hasCustomField = in_array($this->categoryRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('categories.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        if(!auth()->user()->hasPermissionTo('categories.store')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $input = $request->all();
        $input['name']    = $input['name'];
        $input['name_en'] = $input['name_en'];
        $category = $this->categoryRepository->where('name',$input['name'])->first();
        if(!empty($category)){
            Flash::error(__('lang.error', ['operator' => __('lang.category')]));
        } else {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
            try {

                $category = $this->categoryRepository->create($input);

                if($request->file('categoryImage')) {

                    $imageName = uniqid() . $request->file('categoryImage')->getClientOriginalName();

                    $imageName = preg_replace('/\s+/', '_', $imageName);

                    $request->file('categoryImage')->move(public_path('storage/categoriesPic'), $imageName);

                    $category->update(['image' => $imageName]);


                }

                Flash::success(trans('lang.store_operation'));

            } catch (ValidatorException $e) {
                Flash::error($e->getMessage());
            }
            return redirect(route('categories.index'));
        }
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
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(trans('lang.cat_not_found'));

            return redirect(route('categories.index'));
        }

        return view('categories.show')->with('category', $category);
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
        if(!auth()->user()->hasPermissionTo('categories.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $category = $this->categoryRepository->findWithoutFail($id);


        if (empty($category)) {
            Flash::error(trans('lang.cat_not_found'));

            return redirect(route('categories.index'));
        }
        $customFieldsValues = $category->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        $hasCustomField = in_array($this->categoryRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('categories.edit')->with('category', $category)->with("customFields", isset($html) ? $html : false);
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
        if(!auth()->user()->hasPermissionTo('categories.update')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(trans('lang.cat_not_found'));
            return redirect(route('categories.index'));
        }
        $input = $request->all();
        $input['name']=$input['name'];
        $input['name_en']=$input['name_en'];
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $category = $this->categoryRepository->update($input, $id);

            if(!empty($request->file('categoryImage'))) {

                $imageName = uniqid() . $request->file('categoryImage')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('categoryImage')->move(public_path('storage/categoriesPic'), $imageName);

                $category->update(['image' => $imageName]);


            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('categories.index'));
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

        if(!auth()->user()->hasPermissionTo('categories.destroy')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(trans('lang.cat_not_found'));

            return redirect(route('categories.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('categories.index'));
    }

    /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $category = $this->categoryRepository->findWithoutFail($input['id']);
        try {
            if ($category->hasMedia($input['collection'])) {
                $category->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
