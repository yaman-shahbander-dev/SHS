<?php

namespace App\Http\Controllers;

use App\city;
use App\Country;
use App\DataTables\RatingDataTable;
use App\DataTables\ReviewsDataTable;
use App\DataTables\SubCategoriesVendorDataTable;
use App\DataTables\VendorDataTable;

use App\Events\UserRoleChangedEvent;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ReviewsRepositry;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\subCategory;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\Route;
use App\Models\GmapLocation;

class HomeOwnerController extends Controller
{

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;



    /**
     * Display a listing of the Review.
     *
     * @param ReviewsDataTable $reviewDataTable
     * @return Response
     */
    private $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;



    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo, UploadRepository $uploadRepo, CustomFieldRepository $customFieldRepo)
    {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
        $this->uploadRepository = $uploadRepo;
        $this->customFieldRepository = $customFieldRepo;
    }



    public function profile(Request $request,SubCategoriesVendorDataTable $subCategoriesDataTableDataTable)
    {
        $countries=Country::all();
        
        $user = $this->userRepository->findWithoutFail($request->id);
        unset($user->password);
        $customFields = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        //dd($customFieldsValues);
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFields = generateCustomField($customFields, $customFieldsValues);
        }
        if(!empty($user->cities->id))
        {
            $cities=City::where('country_id',$user->cities->country_id)->get();

        }
        else
            $cities=[];
          //  return dd($user->subcategories);
        $user->rating=getRating($user);

        $userCoordinates =  GmapLocation::where('user_id', $request->id)->first();
        
        if(!empty($userCoordinates)) {
            Mapper::map(
                $userCoordinates->latitude,
                $userCoordinates->longitude,
                [
                    'zoom'      => 8,
                    'draggable' => false,
                    'marker'    => true,
                    'markers' => ['title' => 'My Location', 'animation' => 'BOUNCE']
                ]);

                // document.getElementById("gmap").style.
                $style='style="width: 100%; height: 300px"';
        } else {
                $style="";
        }
        
        return $dataTable=$subCategoriesDataTableDataTable->render('settings.users.profile',compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues','countries','cities','style']));
        
    }
}
