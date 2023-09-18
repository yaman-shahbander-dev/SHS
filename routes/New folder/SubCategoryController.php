<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SubCategoriesRepository;

class SubCategoryController extends Controller
{


    /** @var  SubCategoriesRepository */
    private $subcategoryRepository;

    public function __construct(SubCategoriesRepository $subcategoryRepo)
    {
        $this->subcategoryRepository = $subcategoryRepo;
    }


    public function index(Request $request){


        $subcategories = $this->subcategoryRepository->where("category_id", $request->id)->get(['id','name','description']);

$response=$subcategories->toArray();
$i=0;
 foreach ($subcategories as $subcategory)
{
    try{    $response[$i]['photo']=$subcategory->media[0]->getUrlAttribute();
    }
    catch (\Exception $e) {
        $response[$i]['photo']=url('images/image_default.png');
    }
    $i++;

}

        return $this->sendResponse($response, 'SubCategories retrieved successfully');
    }

}
