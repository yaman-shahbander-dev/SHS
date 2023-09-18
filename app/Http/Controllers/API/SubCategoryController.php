<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\subCategory;
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
        if($request->header('devicetoken')) {

            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
        $subcategories = $this->subcategoryRepository->where("category_id", $request->id)->get(['id','name','description']);

            $response=$subcategories->toArray();

            $i=0;
            foreach ($subcategories as $subcategory)
            {
                try{    $response[$i]['photo']=asset('storage/subcategoriesPic').'/'.($subcategory->image==null?'image_default.png':$subcategory->image);
                }
                catch (\Exception $e) {
                    $response[$i]['photo']=url('images/image_default.png');
                }
                $i++;

            }

        return $this->sendResponse($response, 'SubCategories retrieved successfully');
            } catch (\Exception $e) {
                return $this->sendError('error', 401);

            }
        }
        else
            return $this->sendError('You dont have permission', 401);

    }

}
