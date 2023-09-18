<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\SubCategoriesRepository;
use App\Repositories\CustomFieldRepository;
use App\DataTables\SubCategoriesDataTable;
use App\DataTables\SubCaVendorDataTable;
use App\Http\Requests\UpdateSubCategoryRequest;
use Flash;
use App\Repositories\UploadRepository;
use App\subCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class SubCategoryController extends Controller
{

    /** @var  SubCategoriesRepository */
    private $subcategoryRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(SubCategoriesRepository $subcategoryRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->subcategoryRepository = $subcategoryRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param SubCategoriesDataTable $subcategoryDataTable
     * @return Response
     */
    public function index(SubCategoriesDataTable $subcategoryDataTable)
    {           
        if(!auth()->user()->hasPermissionTo('subcategory.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $categories = subCategory::all();
        return $subcategoryDataTable->render('SubCategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermissionTo('subcategory.create')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $hasCustomField = in_array($this->subcategoryRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subcategoryRepository->model());
            $html = generateCustomField($customFields);
        }
        $categories=Category::all();
        if(count($categories)!=0) 
        {
            return view('SubCategories.create', ['categories'=>$categories,'customFields'=> isset($html) ? $html : false]);
        }else{
            return redirect()->back()->with(["error"=> 'Please add category','customFields'=> isset($html) ? $html : false]);
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
        if(!auth()->user()->hasPermissionTo('subcategory.store')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if ($request->category == "0") {
            Flash::error(trans('lang.select_cat'));
            return redirect()->back();
        }
        $subcategories = subCategory::where('name', $request->name)->get();
         foreach ($subcategories as $category) {
            if ($category->category_id == $request->category) {
                Flash::error(trans('lang.subcate_exist'));
                return redirect()->back();
            }
        }
        $input = $request->all();
        $input['category_id']=$input['category'];
        $input['name']=$input['name'];
        $input['name_en']=$input['name_en'];
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subcategoryRepository->model());
        try {
            $subcategory = $this->subcategoryRepository->create($input);
            if($request->file('categoryImage')) {

                $imageName = uniqid() . $request->file('categoryImage')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);
 
                $request->file('categoryImage')->move(public_path('storage/subcategoriesPic'), $imageName);

                $subcategory->update(['image' => $imageName]);   
            }   
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.store_operation'));

        return redirect(route('subcategory.create'));


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
        $subcategory = $this->subcategoryRepository->findWithoutFail($id);

        if (empty($subcategory)) {
            Flash::error(trans('lang.subcat_not_found'));

            return redirect(route('subcategory.index'));
        }

        return view('subcategory.show')->with('category', $subcategory);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasPermissionTo('subcategory.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $categories=Category::all();

        $subcategory = $this->subcategoryRepository->findWithoutFail($id);
       // return $subcategory;


        if (empty($subcategory)) {
            Flash::error(trans('lang.subcat_not_found'));

            return redirect(route('subcategory.index'));
        }

        return view('SubCategories.edit')->with(['subcategory'=> $subcategory,'categories'=>$categories]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param UpdateSubCategoryRequest $request
     */
    public function update($id, UpdateSubCategoryRequest $request)
    {
        if(!auth()->user()->hasPermissionTo('subcategory.update')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $subcategory = $this->subcategoryRepository->findWithoutFail($id);

        if (empty($subcategory)) {
            Flash::error(trans('lang.subcat_not_found'));
            return redirect(route('subcategory.index'));
        }
        $input = $request->all();
        $input['category_id']=$input['category'];
        $input['name']=$input['name'];
        $input['name_en']=$input['name_en'];
        try {
            $subcategory = $this->subcategoryRepository->update($input, $id);

            if(!empty($request->file('categoryImage'))) {

                $imageName = uniqid() . $request->file('categoryImage')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('categoryImage')->move(public_path('storage/subcategoriesPic'), $imageName);

                $subcategory->update(['image' => $imageName]);
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('subcategory.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasPermissionTo('subcategory.destroy')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $subcategory = $this->subcategoryRepository->findWithoutFail($id);

        if (empty($subcategory)) {
            Flash::error(trans('lang.subcat_not_found'));

            return redirect(route('subcategory.index'));
        }

        $this->subcategoryRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('subcategory.index'));
    }
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $subcategory = $this->subcategoryRepository->findWithoutFail($input['id']);
        try {
            if ($subcategory->hasMedia($input['collection'])) {
                $subcategory->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Display a listing of the Category.
     *
     * @param SubCaVendorDataTable $subCaVendorDataTable
     * @return Response
     */

    public function getSubcategoryVendors($id,SubCaVendorDataTable $subCaVendorDataTable) {
       // $subCaVendorDataTable = $this->subcategoryRepository->findWithoutFail($id);

        return $subCaVendorDataTable->with('id',$id)->render('SubCategories.subcategoryvendor');
    }
}
