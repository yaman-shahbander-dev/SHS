<?php

namespace App\Http\Controllers;

use App\city;
use App\Country;
use App\DataTables\SubCategoriesVendorDataTable;
use App\DataTables\VendorDataTable;

use App\Events\UserRoleChangedEvent;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Category;
use App\Repositories\UserRepository;
use App\DataTables\RatingDataTable;
use App\DataTables\ReviewsDataTable;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ReviewsRepositry;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\VendorRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\subCategory;
use DB;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\Route;
use App\Models\GmapLocation;
use App\Models\Fee;
use App\Models\User;
use App\Balance;
use Validator;
use App\Models\Gallery;
//use App\Models\Category;

class VendorController extends Controller
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



    /**
     * Display a listing of the Review.
     *
     * @param ReviewsDataTable $reviewDataTable
     * @return Response
     */
    private $vendorRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;



    public function __construct(
        ReviewsRepositry $reviewRepo,
        VendorRepository $vendorRepo,
        RoleRepository $roleRepo,
        UploadRepository $uploadRepo,
        CustomFieldRepository $customFieldRepo
    ) {
        parent::__construct();
        $this->reviewRepository = $reviewRepo;

        $this->vendorRepository = $vendorRepo;
        $this->roleRepository = $roleRepo;
        $this->uploadRepository = $uploadRepo;
        $this->customFieldRepository = $customFieldRepo;
    }

    public function index(VendorDataTable $vendorDataTable)
    {
        if (!auth()->user()->hasPermissionTo('vendors.index')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $vendorDataTable->render('settings.vendors.index');
    }
    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */

    //    public function create()
    //    {
    //        $role = $this->roleRepository->pluck('name', 'name');
    //        //$role = $role->where('name','vendor');
    //        $role = array(
    //          "vendor" => "vendor"
    //        );
    //
    //        //dd($role);
    //        $rolesSelected = [];
    //        $hasCustomField = in_array($this->vendorRepository->model(), setting('custom_field_models', []));
    //        if ($hasCustomField) {
    //            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorRepository->model());
    //            $html = generateCustomField($customFields);
    //        }
    //
    //        return view('settings.vendors.create')
    //            ->with("role", $role)
    //            ->with("customFields", isset($html) ? $html : false)
    //            ->with("rolesSelected", $rolesSelected);
    //    }



    public function create()
    {

        if (!auth()->user()->hasPermissionTo('vendors.create')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }


        $countries_codes = [
            ['name' => 'Afghanistan', 'code' => 'AF', 'prefix' => '+93'],

            ['name' => 'Åland Islands', 'code' => 'AX', 'prefix' => '+358'],

            ['name' => 'Albania', 'code' => 'AL', 'prefix' => '+355'],

            ['name' => 'Algeria', 'code' => 'DZ', 'prefix' => '+213'],

            ['name' => 'American Samoa', 'code' => 'AS', 'prefix' => '+1'],

            ['name' => 'Andorra', 'code' => 'AD', 'prefix' => '+376'],

            ['name' => 'Angola', 'code' => 'AO', 'prefix' => '+244'],

            ['name' => 'Anguilla', 'code' => 'AI', 'prefix' => '+1'],

            ['name' => 'Antarctica', 'code' => 'AQ', 'prefix' => '+672'],

            ['name' => 'Antigua and Barbuda', 'code' => 'AG', 'prefix' => '+1'],

            ['name' => 'Argentina', 'code' => 'AR', 'prefix' => '+54'],

            ['name' => 'Armenia', 'code' => 'AM', 'prefix' => '+374'],

            ['name' => 'Aruba', 'code' => 'AW', 'prefix' => '+297'],

            ['name' => 'Australia', 'code' => 'AU', 'prefix' => '+61'],

            ['name' => 'Austria', 'code' => 'AT', 'prefix' => '+43'],

            ['name' => 'Azerbaijan', 'code' => 'AZ', 'prefix' => '+994'],

            ['name' => 'Bahamas', 'code' => 'BS', 'prefix' => '+1'],

            ['name' => 'Bahrain', 'code' => 'BH', 'prefix' => '+973'],

            ['name' => 'Bangladesh', 'code' => 'BD', 'prefix' => '+880'],

            ['name' => 'Barbados', 'code' => 'BB', 'prefix' => '+1'],

            ['name' => 'Belarus', 'code' => 'BY', 'prefix' => '+375'],

            ['name' => 'Belgium', 'code' => 'BE', 'prefix' => '+32'],

            ['name' => 'Belize', 'code' => 'BZ', 'prefix' => '+501'],

            ['name' => 'Benin', 'code' => 'BJ', 'prefix' => '+229'],

            ['name' => 'Bermuda', 'code' => 'BM', 'prefix' => '+1'],

            ['name' => 'Bhutan', 'code' => 'BT', 'prefix' => '+975'],

            ['name' => 'Bolivia, Plurinational State of', 'code' => 'BO', 'prefix' => '+591'],

            ['name' => 'Bonaire, Sint Eustatius and Saba', 'code' => 'BQ', 'prefix' => '+599'],

            ['name' => 'Bosnia and Herzegovina', 'code' => 'BA', 'prefix' => '+387'],

            ['name' => 'Botswana', 'code' => 'BW', 'prefix' => '+267'],

            ['name' => 'Brazil', 'code' => 'BR', 'prefix' => '+55'],

            ['name' => 'British Indian Ocean Territory', 'code' => 'IO', 'prefix' => '+246'],

            ['name' => 'Brunei Darussalam', 'code' => 'BN', 'prefix' => '+673'],

            ['name' => 'Bulgaria', 'code' => 'BG', 'prefix' => '+359'],

            ['name' => 'Burkina Faso', 'code' => 'BF', 'prefix' => '+226'],

            ['name' => 'Burundi', 'code' => 'BI', 'prefix' => '+257'],

            ['name' => 'Cambodia', 'code' => 'KH', 'prefix' => '+855'],

            ['name' => 'Cameroon', 'code' => 'CM', 'prefix' => '+237'],

            ['name' => 'Canada', 'code' => 'CA', 'prefix' => '+1'],

            ['name' => 'Cape Verde', 'code' => 'CV', 'prefix' => '+238'],

            ['name' => 'Cayman Islands', 'code' => 'KY', 'prefix' => '+1'],

            ['name' => 'Central African Republic', 'code' => 'CF', 'prefix' => '+236'],

            ['name' => 'Chad', 'code' => 'TD', 'prefix' => '+235'],

            ['name' => 'Chile', 'code' => 'CL', 'prefix' => '+56'],

            ['name' => 'China', 'code' => 'CN', 'prefix' => '+86'],

            ['name' => 'Christmas Island', 'code' => 'CX', 'prefix' => '+61'],

            ['name' => 'Cocos (Keeling) Islands', 'code' => 'CC', 'prefix' => '+61'],

            ['name' => 'Colombia', 'code' => 'CO', 'prefix' => '+57'],

            ['name' => 'Comoros', 'code' => 'KM', 'prefix' => '+269'],

            ['name' => 'Congo', 'code' => 'CG', 'prefix' => '+243'],

            ['name' => 'Congo, the Democratic Republic of the', 'code' => 'CD', 'prefix' => '+243'],

            ['name' => 'Cook Islands', 'code' => 'CK', 'prefix' => '+682'],

            ['name' => 'Costa Rica', 'code' => 'CR', 'prefix' => '+506'],

            ['name' => 'Côte d\'Ivoire', 'code' => 'CI', 'prefix' => '+225'],

            ['name' => 'Croatia', 'code' => 'HR', 'prefix' => '+385'],

            ['name' => 'Cuba', 'code' => 'CU', 'prefix' => '+53'],

            ['name' => 'Curaçao', 'code' => 'CW', 'prefix' => '+599'],

            ['name' => 'Cyprus', 'code' => 'CY', 'prefix' => '+357'],

            ['name' => 'Czech Republic', 'code' => 'CZ', 'prefix' => '+420'],

            ['name' => 'Denmark', 'code' => 'DK', 'prefix' => '+45'],

            ['name' => 'Djibouti', 'code' => 'DJ', 'prefix' => '+253'],

            ['name' => 'Dominica', 'code' => 'DM', 'prefix' => '+1'],

            ['name' => 'Dominican Republic', 'code' => 'DO', 'prefix' => '+1'],

            ['name' => 'Ecuador', 'code' => 'EC', 'prefix' => '+593'],

            ['name' => 'Egypt', 'code' => 'EG', 'prefix' => '+20'],

            ['name' => 'El Salvador', 'code' => 'SV', 'prefix' => '+503'],

            ['name' => 'Equatorial Guinea', 'code' => 'GQ', 'prefix' => '+240'],

            ['name' => 'Eritrea', 'code' => 'ER', 'prefix' => '+291'],

            ['name' => 'Estonia', 'code' => 'EE', 'prefix' => '+372'],

            ['name' => 'Ethiopia', 'code' => 'ET', 'prefix' => '+251'],

            ['name' => 'Falkland Islands (Malvinas)', 'code' => 'FK', 'prefix' => '+500'],

            ['name' => 'Faroe Islands', 'code' => 'FO', 'prefix' => '+298'],

            ['name' => 'Fiji', 'code' => 'FJ', 'prefix' => '+679'],

            ['name' => 'Finland', 'code' => 'FI', 'prefix' => '+358'],

            ['name' => 'France', 'code' => 'FR', 'prefix' => '+33'],

            ['name' => 'French Guiana', 'code' => 'GF', 'prefix' => '+594'],

            ['name' => 'French Polynesia', 'code' => 'PF', 'prefix' => '+689'],

            ['name' => 'French Southern Territories', 'code' => 'TF', 'prefix' => '+262'],

            ['name' => 'Gabon', 'code' => 'GA', 'prefix' => '+241'],

            ['name' => 'Gambia', 'code' => 'GM', 'prefix' => '+220'],

            ['name' => 'Georgia', 'code' => 'GE', 'prefix' => '+995'],

            ['name' => 'Germany', 'code' => 'DE', 'prefix' => '+49'],

            ['name' => 'Ghana', 'code' => 'GH', 'prefix' => '+233'],

            ['name' => 'Gibraltar', 'code' => 'GI', 'prefix' => '+350'],

            ['name' => 'Greece', 'code' => 'GR', 'prefix' => '+30'],

            ['name' => 'Greenland', 'code' => 'GL', 'prefix' => '+299'],

            ['name' => 'Grenada', 'code' => 'GD', 'prefix' => '+1'],

            ['name' => 'Guadeloupe', 'code' => 'GP', 'prefix' => '+590'],

            ['name' => 'Guam', 'code' => 'GU', 'prefix' => '+1'],

            ['name' => 'Guatemala', 'code' => 'GT', 'prefix' => '+502'],

            ['name' => 'Guernsey', 'code' => 'GG', 'prefix' => '+44'],

            ['name' => 'Guinea', 'code' => 'GN', 'prefix' => '+224'],

            ['name' => 'Guinea-Bissau', 'code' => 'GW', 'prefix' => '+245'],

            ['name' => 'Guyana', 'code' => 'GY', 'prefix' => '+592'],

            ['name' => 'Haiti', 'code' => 'HT', 'prefix' => '+509'],

            ['name' => 'Holy See (Vatican City State)', 'code' => 'VA', 'prefix' => '+379'],

            ['name' => 'Honduras', 'code' => 'HN', 'prefix' => '+504'],

            ['name' => 'Hong Kong', 'code' => 'HK', 'prefix' => '+852'],

            ['name' => 'Hungary', 'code' => 'HU', 'prefix' => '+36'],

            ['name' => 'Iceland', 'code' => 'IS', 'prefix' => '+354'],

            ['name' => 'India', 'code' => 'IN', 'prefix' => '+91'],

            ['name' => 'Indonesia', 'code' => 'ID', 'prefix' => '+62'],

            ['name' => 'Iran, Islamic Republic of', 'code' => 'IR', 'prefix' => '+98'],

            ['name' => 'Iraq', 'code' => 'IQ', 'prefix' => '+964'],

            ['name' => 'Ireland', 'code' => 'IE', 'prefix' => '+353'],

            ['name' => 'Isle of Man', 'code' => 'IM', 'prefix' => '+44'],

            ['name' => 'Israel', 'code' => 'IL', 'prefix' => '+972'],

            ['name' => 'Italy', 'code' => 'IT', 'prefix' => '+39'],

            ['name' => 'Jamaica', 'code' => 'JM', 'prefix' => '+1'],

            ['name' => 'Japan', 'code' => 'JP', 'prefix' => '+81'],

            ['name' => 'Jersey', 'code' => 'JE', 'prefix' => '+44'],

            ['name' => 'Jordan', 'code' => 'JO', 'prefix' => '+962'],

            ['name' => 'Kazakhstan', 'code' => 'KZ', 'prefix' => '+7'],

            ['name' => 'Kenya', 'code' => 'KE', 'prefix' => '+254'],

            ['name' => 'Kiribati', 'code' => 'KI', 'prefix' => '+686'],

            ['name' => 'Korea, Democratic People\'s Republic of', 'code' => 'KP', 'prefix' => '+850'],

            ['name' => 'Korea, Republic of', 'code' => 'KR', 'prefix' => '+82'],

            ['name' => 'Kuwait', 'code' => 'KW', 'prefix' => '+965'],

            ['name' => 'Kyrgyzstan', 'code' => 'KG', 'prefix' => '+996'],

            ['name' => 'Lao People\'s Democratic Republic', 'code' => 'LA', 'prefix' => '+856'],

            ['name' => 'Latvia', 'code' => 'LV', 'prefix' => '+371'],

            ['name' => 'Lebanon', 'code' => 'LB', 'prefix' => '+961'],

            ['name' => 'Lesotho', 'code' => 'LS', 'prefix' => '+266'],

            ['name' => 'Liberia', 'code' => 'LR', 'prefix' => '+231'],

            ['name' => 'Libya', 'code' => 'LY', 'prefix' => '+218'],

            ['name' => 'Liechtenstein', 'code' => 'LI', 'prefix' => '+423'],

            ['name' => 'Lithuania', 'code' => 'LT', 'prefix' => '+370'],

            ['name' => 'Luxembourg', 'code' => 'LU', 'prefix' => '+352'],

            ['name' => 'Macao', 'code' => 'MO', 'prefix' => '+853'],

            ['name' => 'Macedonia, the Former Yugoslav Republic of', 'code' => 'MK', 'prefix' => '+389'],

            ['name' => 'Madagascar', 'code' => 'MG', 'prefix' => '+261'],

            ['name' => 'Malawi', 'code' => 'MW', 'prefix' => '+265'],

            ['name' => 'Malaysia', 'code' => 'MY', 'prefix' => '+60'],

            ['name' => 'Maldives', 'code' => 'MV', 'prefix' => '+960'],

            ['name' => 'Mali', 'code' => 'ML', 'prefix' => '+223'],

            ['name' => 'Malta', 'code' => 'MT', 'prefix' => '+356'],

            ['name' => 'Marshall Islands', 'code' => 'MH', 'prefix' => '+692'],

            ['name' => 'Martinique', 'code' => 'MQ', 'prefix' => '+596'],

            ['name' => 'Mauritania', 'code' => 'MR', 'prefix' => '+222'],

            ['name' => 'Mauritius', 'code' => 'MU', 'prefix' => '+230'],

            ['name' => 'Mayotte', 'code' => 'YT', 'prefix' => '+262'],

            ['name' => 'Mexico', 'code' => 'MX', 'prefix' => '+52'],

            ['name' => 'Micronesia, Federated States of', 'code' => 'FM', 'prefix' => '+691'],

            ['name' => 'Moldova, Republic of', 'code' => 'MD', 'prefix' => '+373'],

            ['name' => 'Monaco', 'code' => 'MC', 'prefix' => '+377'],

            ['name' => 'Mongolia', 'code' => 'MN', 'prefix' => '+976'],

            ['name' => 'Montenegro', 'code' => 'ME', 'prefix' => '+382'],

            ['name' => 'Montserrat', 'code' => 'MS', 'prefix' => '+1'],

            ['name' => 'Morocco', 'code' => 'MA', 'prefix' => '+212'],

            ['name' => 'Mozambique', 'code' => 'MZ', 'prefix' => '+258'],

            ['name' => 'Myanmar', 'code' => 'MM', 'prefix' => '+95'],

            ['name' => 'Namibia', 'code' => 'NA', 'prefix' => '+264'],

            ['name' => 'Nauru', 'code' => 'NR', 'prefix' => '+674'],

            ['name' => 'Nepal', 'code' => 'NP', 'prefix' => '+977'],

            ['name' => 'Netherlands', 'code' => 'NL', 'prefix' => '+31'],

            ['name' => 'New Caledonia', 'code' => 'NC', 'prefix' => '+687'],

            ['name' => 'New Zealand', 'code' => 'NZ', 'prefix' => '+64'],

            ['name' => 'Nicaragua', 'code' => 'NI', 'prefix' => '+505'],

            ['name' => 'Niger', 'code' => 'NE', 'prefix' => '+227'],

            ['name' => 'Nigeria', 'code' => 'NG', 'prefix' => '+234'],

            ['name' => 'Niue', 'code' => 'NU', 'prefix' => '+683'],

            ['name' => 'Norfolk Island', 'code' => 'NF', 'prefix' => '+672'],

            ['name' => 'Northern Mariana Islands', 'code' => 'MP', 'prefix' => '+1'],

            ['name' => 'Norway', 'code' => 'NO', 'prefix' => '+47'],

            ['name' => 'Oman', 'code' => 'OM', 'prefix' => '+968'],

            ['name' => 'Pakistan', 'code' => 'PK', 'prefix' => '+92'],

            ['name' => 'Palau', 'code' => 'PW', 'prefix' => '+680'],

            ['name' => 'Palestine, State of', 'code' => 'PS', 'prefix' => '+970'],

            ['name' => 'Panama', 'code' => 'PA', 'prefix' => '+507'],

            ['name' => 'Papua New Guinea', 'code' => 'PG', 'prefix' => '+675'],

            ['name' => 'Paraguay', 'code' => 'PY', 'prefix' => '+595'],

            ['name' => 'Peru', 'code' => 'PE', 'prefix' => '+51'],

            ['name' => 'Philippines', 'code' => 'PH', 'prefix' => '+63'],

            ['name' => 'Pitcairn', 'code' => 'PN', 'prefix' => '+64'],

            ['name' => 'Poland', 'code' => 'PL', 'prefix' => '+48'],

            ['name' => 'Portugal', 'code' => 'PT', 'prefix' => '+351'],

            ['name' => 'Puerto Rico', 'code' => 'PR', 'prefix' => '+1'],

            ['name' => 'Qatar', 'code' => 'QA', 'prefix' => '+974'],

            ['name' => 'Réunion', 'code' => 'RE', 'prefix' => '+262'],

            ['name' => 'Romania', 'code' => 'RO', 'prefix' => '+40'],

            ['name' => 'Russian Federation', 'code' => 'RU', 'prefix' => '+7'],

            ['name' => 'Rwanda', 'code' => 'RW', 'prefix' => '+250'],

            ['name' => 'Saint Barthélemy', 'code' => 'BL', 'prefix' => '+590'],

            ['name' => 'Saint Helena, Ascension and Tristan da Cunha', 'code' => 'SH', 'prefix' => '+290'],

            ['name' => 'Saint Kitts and Nevis', 'code' => 'KN', 'prefix' => '+1'],

            ['name' => 'Saint Lucia', 'code' => 'LC', 'prefix' => '+1'],

            ['name' => 'Saint Martin (French part)', 'code' => 'MF', 'prefix' => '+590'],

            ['name' => 'Saint Pierre and Miquelon', 'code' => 'PM', 'prefix' => '+508'],

            ['name' => 'Saint Vincent and the Grenadines', 'code' => 'VC', 'prefix' => '+1'],

            ['name' => 'Samoa', 'code' => 'WS', 'prefix' => '+685'],

            ['name' => 'San Marino', 'code' => 'SM', 'prefix' => '+378'],

            ['name' => 'Sao Tome and Principe', 'code' => 'ST', 'prefix' => '+239'],

            ['name' => 'Saudi Arabia', 'code' => 'SA', 'prefix' => '+966'],

            ['name' => 'Senegal', 'code' => 'SN', 'prefix' => '+221'],

            ['name' => 'Serbia', 'code' => 'RS', 'prefix' => '+381'],

            ['name' => 'Seychelles', 'code' => 'SC', 'prefix' => '+248'],

            ['name' => 'Sierra Leone', 'code' => 'SL', 'prefix' => '+232'],

            ['name' => 'Singapore', 'code' => 'SG', 'prefix' => '+65'],

            ['name' => 'Sint Maarten (Dutch part)', 'code' => 'SX', 'prefix' => '+599'],

            ['name' => 'Slovakia', 'code' => 'SK', 'prefix' => '+421'],

            ['name' => 'Slovenia', 'code' => 'SI', 'prefix' => '+386'],

            ['name' => 'Solomon Islands', 'code' => 'SB', 'prefix' => '+677'],

            ['name' => 'Somalia', 'code' => 'SO', 'prefix' => '+252'],

            ['name' => 'South Africa', 'code' => 'ZA', 'prefix' => '+27'],

            ['name' => 'South Georgia and the South Sandwich Islands', 'code' => 'GS', 'prefix' => '+500'],

            ['name' => 'South Sudan', 'code' => 'SS', 'prefix' => '+211'],

            ['name' => 'Spain', 'code' => 'ES', 'prefix' => '+34'],

            ['name' => 'Sri Lanka', 'code' => 'LK', 'prefix' => '+94'],

            ['name' => 'Sudan', 'code' => 'SD', 'prefix' => '+249'],

            ['name' => 'Suriname', 'code' => 'SR', 'prefix' => '+597'],

            ['name' => 'Svalbard and Jan Mayen', 'code' => 'SJ', 'prefix' => '+47'],

            ['name' => 'Swaziland', 'code' => 'SZ', 'prefix' => '+268'],

            ['name' => 'Sweden', 'code' => 'SE', 'prefix' => '+46'],

            ['name' => 'Switzerland', 'code' => 'CH', 'prefix' => '+41'],

            ['name' => 'Syrian Arab Republic', 'code' => 'SY', 'prefix' => '+963'],

            ['name' => 'Taiwan', 'code' => 'TW', 'prefix' => '+886'],

            ['name' => 'Tajikistan', 'code' => 'TJ', 'prefix' => '+992'],

            ['name' => 'Tanzania, United Republic of', 'code' => 'TZ', 'prefix' => '+255'],

            ['name' => 'Thailand', 'code' => 'TH', 'prefix' => '+66'],

            ['name' => 'Timor-Leste', 'code' => 'TL', 'prefix' => '+670'],

            ['name' => 'Togo', 'code' => 'TG', 'prefix' => '+228'],

            ['name' => 'Tokelau', 'code' => 'TK', 'prefix' => '+690'],

            ['name' => 'Tonga', 'code' => 'TO', 'prefix' => '+676'],

            ['name' => 'Trinidad and Tobago', 'code' => 'TT', 'prefix' => '+868'],

            ['name' => 'Tunisia', 'code' => 'TN', 'prefix' => '+216'],

            ['name' => 'Turkey', 'code' => 'TR', 'prefix' => '+90'],

            ['name' => 'Turkmenistan', 'code' => 'TM', 'prefix' => '+993'],

            ['name' => 'Turks and Caicos Islands', 'code' => 'TC', 'prefix' => '+1'],

            ['name' => 'Tuvalu', 'code' => 'TV', 'prefix' => '+688'],

            ['name' => 'Uganda', 'code' => 'UG', 'prefix' => '+256'],

            ['name' => 'Ukraine', 'code' => 'UA', 'prefix' => '+380'],

            ['name' => 'United Arab Emirates', 'code' => 'AE', 'prefix' => '+971'],

            ['name' => 'United Kingdom', 'code' => 'GB', 'prefix' => '+44'],

            ['name' => 'United States', 'code' => 'US', 'prefix' => '+1'],

            ['name' => 'United States Minor Outlying Islands', 'code' => 'UM', 'prefix' => '+246'],

            ['name' => 'Uruguay', 'code' => 'UY', 'prefix' => '+598'],

            ['name' => 'Uzbekistan', 'code' => 'UZ', 'prefix' => '+998'],

            ['name' => 'Vanuatu', 'code' => 'VU', 'prefix' => '+678'],

            ['name' => 'Venezuela, Bolivarian Republic of', 'code' => 'VE', 'prefix' => '+58'],

            ['name' => 'Vietnam', 'code' => 'VN', 'prefix' => '+84'],

            ['name' => 'Virgin Islands, British', 'code' => 'VG', 'prefix' => '+1'],

            ['name' => 'Virgin Islands, U.S.', 'code' => 'VI', 'prefix' => '+1'],

            ['name' => 'Wallis and Futuna', 'code' => 'WF', 'prefix' => '+681'],

            ['name' => 'Western Sahara', 'code' => 'EH', 'prefix' => '+212'],

            ['name' => 'Yemen', 'code' => 'YE', 'prefix' => '+967'],

            ['name' => 'Zambia', 'code' => 'ZM', 'prefix' => '+260'],

            ['name' => 'Zimbabwe', 'code' => 'ZW', 'prefix' => '+263'],
        ];

$categories=Category::all();
        $countries = Country::all();
        //        $cities=City::all();
        $role = $this->roleRepository->pluck('name', 'name');

        $rolesSelected = [];
        $hasCustomField = in_array($this->vendorRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorRepository->model());
            $html = generateCustomField($customFields);
        }
        $style = "";

        Mapper::map(
            36.216667,
            37.166668,
                [
                    'zoom'         => 8,
                    'draggable'    => true,
                    'marker'       => true,
                    'markers'      => ['title' => 'My Location', 'animation' => 'BOUNCE'],
                    'eventDragEnd' =>  '
                                        document.getElementById("latitude").value = event.latLng.lat(),
                                        document.getElementById("longitude").value = event.latLng.lng()',
                ]
            );

        return view('settings.vendors.create')
            ->with("role", $role)
            ->with("customFields", isset($html) ? $html : false)
            ->with("rolesSelected", $rolesSelected)
            ->with("style", $style)
            ->with("countries", $countries)
            ->with("categories", $categories)
            ->with('countries_codes', $countries_codes);
    }



    /**
     * Display a listing of the Review.
     *
     * @param ReviewsDataTable $reviewDataTable
     * @return Response
     */
    public function profile(Request $request, SubCategoriesVendorDataTable $subCategoriesDataTableDataTable)
    {
        if (!auth()->user()->hasPermissionTo('vendors.profile')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $categories=Category::all();
        $countries = Country::all();

        $user = $this->vendorRepository->findWithoutFail($request->id);
        unset($user->password);
        $customFields = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        //dd($customFieldsValues);
        $hasCustomField = in_array($this->vendorRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorRepository->model());
            $customFields = generateCustomField($customFields, $customFieldsValues);
        }
        if (!empty($user->cities->id)) {
            $cities = City::where('country_id', $user->cities->country_id)->get();
        } else
            $cities = [];
        //  return dd($user->subcategories);
        $user->rating = getRating($user);

        $userCoordinates =  GmapLocation::where('user_id', $request->id)->first();

        if (!empty($userCoordinates)) {

            Mapper::map(
                $userCoordinates->latitude,
                $userCoordinates->longitude,
                [
                    'zoom'         => 8,
                    'draggable'    => false,
                    'marker'       => true,
                    'markers'      => ['title' => 'My Location', 'animation' => 'BOUNCE'],
                ]
            );

            $style = 'style="width: 100%; height: 300px"';
        } else {
            $style = "";
        }

        $favoriteVendor = $user->homeOwnerFavorite; // Users who added this vendor as a favorite
        $SP_Galleries = $user->gallery;

        return $dataTable = $subCategoriesDataTableDataTable->render('settings.vendors.profile', compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues', 'countries', 'cities', 'style', 'favoriteVendor', 'SP_Galleries','categories']));



        //  $subcategories = subCategory::all();

        //  return dd($user->clients);


        //  return $dataTable=$reviewsDataTable->render('settings.vendors.profile',compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues','countries','cities', 'subcategories']));


        //  return view('settings.vendors.profile', compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues','countries','cities','dataTable']));
    }

    public function store(CreateUserRequest $request)
    {
        $json = json_decode($request->dayWorkingHours);
        $days = [];
        $i = 0;
        foreach ($json as $day) {
            $days[$i]['day_id'] = $day->day_id;
            $days[$i]['start'] = $day->start;
            $days[$i]['end'] = $day->end;
            $i++;
        }

        if (!auth()->user()->hasPermissionTo('vendors.store')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if ($request->city == "0") {
            Flash::warning(trans('lang.select_country_city'));
            return redirect()->back();
        }

        if ($request->input('email') == null && $request->input('phone') == null) {
            Flash::error('Either email or phone should be filled!');
            return redirect()->back();
        }

        if ($request->input('website')) {
            if (!filter_var($request->input('website'), FILTER_VALIDATE_URL)) {
                Flash::error('Enter a valid url');
                return redirect()->back();
            }
        }


        $input = $request->all();

        $input['user_id'] = Auth()->user()->id;
        $input['password'] = Hash::make($input['password']);
        $input['country_prefix'] = $request->countries_code;
        $input['facebook'] = $request->facebook;
        $input['instagram'] = $request->instagram;

        while (true) {
            $payment_id = '#' . rand(1000, 9999) . rand(1000, 9999);
            if (!(User::where('payment_id', $payment_id)->exists())) {
                break;
            } else continue;
        }
        $input['approved_vendor']=1;
        $input['is_verified']=1;
        $input['language'] = $request->input('language') == null ? '' : $request->input('language', '');

        $input['phone'] = $request->input('phone') == null ? '' : $request->countries_code . $request->input('phone', '');

        $input['payment_id'] = $payment_id;
        $balance = new Balance();
        $balance->balance = 0.0;
        $balance->save();
        $input['balance_id'] = $balance->id;

        $input['city_id'] = $request->city;
        $token = openssl_random_pseudo_bytes(16);
        $user = $this->vendorRepository->create($input);
        $user->days()->sync($days);
        // saving location of SP
        $location = new GmapLocation();
        $location->user_id   = $user->id;
        $location->latitude  = $request->latitude;
        $location->longitude = $request->longitude;
        $location->save();
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($user->id . $token);
        $input['device_token'] = $token;
        $user = $this->vendorRepository->update($input, $user->id);
        $subCategory=explode(',',$input['subcategorySelected']);
        if ($input['subcategorySelected'] != null) {
            $subarray = array_map('intval', explode(',', $input['subcategorySelected'] ));
            $user->subcategories()->sync($subarray);
        }

        $user->assignRole('vendor');
        //$user->assignRole($request->roles);

        try {


            if ($request->file('avatar')) {

                $imageName = uniqid() . $request->file('avatar')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('avatar')->move(public_path('storage/Avatar'), $imageName);

                $user->avatar = $imageName;
                $user->save();
            }

            if ($request->file('background_SP')) {

                $imageName = uniqid() . $request->file('background_SP')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('background_SP')->move(public_path('storage/vendors_background'), $imageName);

                $user->background_profile = $imageName;

                $user->save();
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.store_operation'));

        $categories = Category::all();

        return view('special_offers.create', [
            'categories' => $categories,
            'vendors'    => $user,
            'url'        => '/special/offers/create',
            'customFields'=> isset($html) ? $html : false]);

        // return redirect(route('vendors.index'));
    }

    public function featuredfeeFunction()
    {

        if (!auth()->user()->hasPermissionTo('vendors.fee')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $count = count(Fee::all()); // if there is an old fee value
        if ($count > 0) {
            $value = Fee::all('fee_amount');
            $value = $value[0]['fee_amount'];
            return view('settings.vendors.featuredVendorfee')->with('count', $count)
                ->with('value', $value);
        } else {
            return view('settings.vendors.featuredVendorfee')->with('count', $count);
        }
    }

    public function savefeeFunction(Request $request)
    {

        if (!auth()->user()->hasPermissionTo('vendors.feeSave')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $check = Fee::all();
        if (count($check) == 0) {
            $newfee = new Fee;

            $amount  = strip_tags($request->fee_amount);

            if (preg_match('/[a-zA-Z]/', $amount)) {
                Flash::Error(trans('lang.only_numbers'));
                return redirect(route('vendors.index'));
            }


            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $amount)) {
                Flash::Error(trans('lang.only_numbers'));
                return redirect(route('vendors.index'));
            }

            if ($amount < 0) {
                Flash::Error(trans('lang.negative_amount'));
                return redirect(route('vendors.index'));
            }

            $newfee->fee_amount = $amount;

            $newfee->save();

            Flash::success(trans('lang.store_operation'));
            return redirect(route('vendors.index'));
        } else {

            $amount  = strip_tags($request->fee_amount);

            if (preg_match('/[a-zA-Z]/', $amount)) {
                Flash::Error(trans('lang.only_numbers'));
                return redirect(route('vendors.index'));
            }


            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $amount)) {
                Flash::Error(trans('lang.only_numbers'));
                return redirect(route('vendors.index'));
            }

            if ($amount < 0) {
                Flash::Error(trans('lang.negative_amount'));
                return redirect(route('vendors.index'));
            }

            Fee::first()->update([
                'fee_amount' => $amount
            ]);

            Flash::success(trans('lang.fee_update'));
            return redirect(route('vendors.index'));
        }
    }

    public function update($id, Request $request)
    {
        $json = json_decode($request->dayWorkingHours);
        $days = [];
        $i = 0;
        foreach ($json as $day) {
            $days[$i]['day_id'] = $day->day_id;
            $days[$i]['start'] = $day->start;
            $days[$i]['end'] = $day->end;
            $i++;
        }
//return dd($days);

        if (!auth()->user()->hasPermissionTo('vendors.update')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.profile'));
        }
        if ($id == 1) {
            Flash::error('Permission denied');
            return redirect(route('users.profile'));
        }
        if ($request->city == "0") {
            Flash::warning(trans('lang.select_country_city'));
            return redirect()->back();
        }

        if ($request->input('email') == null && $request->input('phone') == null) {
            Flash::success(trans('lang.require_email_phone'));
            return redirect()->back();
        }

        $input = $request->all();

        $input['user_id'] = Auth()->user()->id;
        $input['password'] = Hash::make($input['password']);

        $input['language'] = $request->input('language') == null ? '' : $request->input('language', '');
        $input['phone'] = $request->input('phone') == null ? '' : $request->countries_code . $request->input('phone', '');

        $input['city_id'] = $request->city;

        $input['country_prefix'] = $request->countries_code;

        $input['facebook'] = $request->facebook;

        $input['instagram'] = $request->instagram;



        unset($input['email']);

        unset($input['phone']);

        $user = $this->vendorRepository->update($input, $id);
        $user->days()->detach();
        $user->days()->sync($days);
//        return dd($days);

        $location = GmapLocation::where('user_id', $user->id)->first();

        if(empty($location)) {
            $location = new GmapLocation();
            $location->user_id   = $user->id;
            $location->latitude  = $request->latitude;
            $location->longitude = $request->longitude;
            $location->save();
        } else {
            $location->latitude  = $request->latitude;
            $location->longitude = $request->longitude;
            $location->save();
        }


        if ($user->email != $request->email) {

            $checkEmail = User::where('email', '=', $request->email)->first();

            if ($checkEmail != null) {
                Flash::error(trans('validation.email'));

                return redirect(route('vendors.edit', [$user->id]));
            } else {
                $user->email = $request->email;
            }
        }

        if ($user->phone != $request->phone) {

            $checkPhone = User::where('phone', '=', $request->phone)->first();

            if ($checkPhone != null) {
                Flash::error(trans('validation.phone'));

                return redirect(route('vendors.edit', [$user->id]));
            } else {
                $user->phone = $request->phone;
            }

        }
//        return dd( $input['subcategorySelected']);

        if ($input['subcategorySelected'] != null) {
        $subarray = array_map('intval', explode(',', $input['subcategorySelected'] ));
            $user->subcategories()->sync($subarray);
        }
        else
            $user->subcategories()->detach();
            $user->save();

            //DB::table('model_has_roles')->where('model_id', $user->id)->delete();

        //$user->assignRole($request->roles);

        try {

            if ($request->file('avatar')) {

                $imageName = uniqid() . $request->file('avatar')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('avatar')->move(public_path('storage/Avatar'), $imageName);

                try {
                    unlink(public_path('storage/Avatar') . '/' . $user->avatar);
                } catch (\Exception $e) {
                }

                $user->avatar = $imageName;
                $user->save();
            }


            if ($request->file('background_SP')) {

                $imageName = uniqid() . $request->file('background_SP')->getClientOriginalName();

                $imageName = preg_replace('/\s+/', '_', $imageName);

                $request->file('background_SP')->move(public_path('storage/vendors_background'), $imageName);

                try {
                    unlink(public_path('storage/vendors_background') . '/' . $user->background_profile);
                } catch (\Exception $e) {
                }

                $user->background_profile = $imageName;

                $user->save();
            }


        }
        catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(trans('lang.update_operation'));

        return redirect(route('vendors.index'));
    }

    public function edit($id)
    {

        if (!auth()->user()->hasPermissionTo('vendors.edit')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if ($id == 1) {
            Flash::success(trans('lang.Permission_denied'));
            return redirect(route('users.index'));
        }


        $countries_codes = [
            ['name' => 'Afghanistan', 'code' => 'AF', 'prefix' => '+93'],

            ['name' => 'Åland Islands', 'code' => 'AX', 'prefix' => '+358'],

            ['name' => 'Albania', 'code' => 'AL', 'prefix' => '+355'],

            ['name' => 'Algeria', 'code' => 'DZ', 'prefix' => '+213'],

            ['name' => 'American Samoa', 'code' => 'AS', 'prefix' => '+1'],

            ['name' => 'Andorra', 'code' => 'AD', 'prefix' => '+376'],

            ['name' => 'Angola', 'code' => 'AO', 'prefix' => '+244'],

            ['name' => 'Anguilla', 'code' => 'AI', 'prefix' => '+1'],

            ['name' => 'Antarctica', 'code' => 'AQ', 'prefix' => '+672'],

            ['name' => 'Antigua and Barbuda', 'code' => 'AG', 'prefix' => '+1'],

            ['name' => 'Argentina', 'code' => 'AR', 'prefix' => '+54'],

            ['name' => 'Armenia', 'code' => 'AM', 'prefix' => '+374'],

            ['name' => 'Aruba', 'code' => 'AW', 'prefix' => '+297'],

            ['name' => 'Australia', 'code' => 'AU', 'prefix' => '+61'],

            ['name' => 'Austria', 'code' => 'AT', 'prefix' => '+43'],

            ['name' => 'Azerbaijan', 'code' => 'AZ', 'prefix' => '+994'],

            ['name' => 'Bahamas', 'code' => 'BS', 'prefix' => '+1'],

            ['name' => 'Bahrain', 'code' => 'BH', 'prefix' => '+973'],

            ['name' => 'Bangladesh', 'code' => 'BD', 'prefix' => '+880'],

            ['name' => 'Barbados', 'code' => 'BB', 'prefix' => '+1'],

            ['name' => 'Belarus', 'code' => 'BY', 'prefix' => '+375'],

            ['name' => 'Belgium', 'code' => 'BE', 'prefix' => '+32'],

            ['name' => 'Belize', 'code' => 'BZ', 'prefix' => '+501'],

            ['name' => 'Benin', 'code' => 'BJ', 'prefix' => '+229'],

            ['name' => 'Bermuda', 'code' => 'BM', 'prefix' => '+1'],

            ['name' => 'Bhutan', 'code' => 'BT', 'prefix' => '+975'],

            ['name' => 'Bolivia, Plurinational State of', 'code' => 'BO', 'prefix' => '+591'],

            ['name' => 'Bonaire, Sint Eustatius and Saba', 'code' => 'BQ', 'prefix' => '+599'],

            ['name' => 'Bosnia and Herzegovina', 'code' => 'BA', 'prefix' => '+387'],

            ['name' => 'Botswana', 'code' => 'BW', 'prefix' => '+267'],

            ['name' => 'Brazil', 'code' => 'BR', 'prefix' => '+55'],

            ['name' => 'British Indian Ocean Territory', 'code' => 'IO', 'prefix' => '+246'],

            ['name' => 'Brunei Darussalam', 'code' => 'BN', 'prefix' => '+673'],

            ['name' => 'Bulgaria', 'code' => 'BG', 'prefix' => '+359'],

            ['name' => 'Burkina Faso', 'code' => 'BF', 'prefix' => '+226'],

            ['name' => 'Burundi', 'code' => 'BI', 'prefix' => '+257'],

            ['name' => 'Cambodia', 'code' => 'KH', 'prefix' => '+855'],

            ['name' => 'Cameroon', 'code' => 'CM', 'prefix' => '+237'],

            ['name' => 'Canada', 'code' => 'CA', 'prefix' => '+1'],

            ['name' => 'Cape Verde', 'code' => 'CV', 'prefix' => '+238'],

            ['name' => 'Cayman Islands', 'code' => 'KY', 'prefix' => '+1'],

            ['name' => 'Central African Republic', 'code' => 'CF', 'prefix' => '+236'],

            ['name' => 'Chad', 'code' => 'TD', 'prefix' => '+235'],

            ['name' => 'Chile', 'code' => 'CL', 'prefix' => '+56'],

            ['name' => 'China', 'code' => 'CN', 'prefix' => '+86'],

            ['name' => 'Christmas Island', 'code' => 'CX', 'prefix' => '+61'],

            ['name' => 'Cocos (Keeling) Islands', 'code' => 'CC', 'prefix' => '+61'],

            ['name' => 'Colombia', 'code' => 'CO', 'prefix' => '+57'],

            ['name' => 'Comoros', 'code' => 'KM', 'prefix' => '+269'],

            ['name' => 'Congo', 'code' => 'CG', 'prefix' => '+243'],

            ['name' => 'Congo, the Democratic Republic of the', 'code' => 'CD', 'prefix' => '+243'],

            ['name' => 'Cook Islands', 'code' => 'CK', 'prefix' => '+682'],

            ['name' => 'Costa Rica', 'code' => 'CR', 'prefix' => '+506'],

            ['name' => 'Côte d\'Ivoire', 'code' => 'CI', 'prefix' => '+225'],

            ['name' => 'Croatia', 'code' => 'HR', 'prefix' => '+385'],

            ['name' => 'Cuba', 'code' => 'CU', 'prefix' => '+53'],

            ['name' => 'Curaçao', 'code' => 'CW', 'prefix' => '+599'],

            ['name' => 'Cyprus', 'code' => 'CY', 'prefix' => '+357'],

            ['name' => 'Czech Republic', 'code' => 'CZ', 'prefix' => '+420'],

            ['name' => 'Denmark', 'code' => 'DK', 'prefix' => '+45'],

            ['name' => 'Djibouti', 'code' => 'DJ', 'prefix' => '+253'],

            ['name' => 'Dominica', 'code' => 'DM', 'prefix' => '+1'],

            ['name' => 'Dominican Republic', 'code' => 'DO', 'prefix' => '+1'],

            ['name' => 'Ecuador', 'code' => 'EC', 'prefix' => '+593'],

            ['name' => 'Egypt', 'code' => 'EG', 'prefix' => '+20'],

            ['name' => 'El Salvador', 'code' => 'SV', 'prefix' => '+503'],

            ['name' => 'Equatorial Guinea', 'code' => 'GQ', 'prefix' => '+240'],

            ['name' => 'Eritrea', 'code' => 'ER', 'prefix' => '+291'],

            ['name' => 'Estonia', 'code' => 'EE', 'prefix' => '+372'],

            ['name' => 'Ethiopia', 'code' => 'ET', 'prefix' => '+251'],

            ['name' => 'Falkland Islands (Malvinas)', 'code' => 'FK', 'prefix' => '+500'],

            ['name' => 'Faroe Islands', 'code' => 'FO', 'prefix' => '+298'],

            ['name' => 'Fiji', 'code' => 'FJ', 'prefix' => '+679'],

            ['name' => 'Finland', 'code' => 'FI', 'prefix' => '+358'],

            ['name' => 'France', 'code' => 'FR', 'prefix' => '+33'],

            ['name' => 'French Guiana', 'code' => 'GF', 'prefix' => '+594'],

            ['name' => 'French Polynesia', 'code' => 'PF', 'prefix' => '+689'],

            ['name' => 'French Southern Territories', 'code' => 'TF', 'prefix' => '+262'],

            ['name' => 'Gabon', 'code' => 'GA', 'prefix' => '+241'],

            ['name' => 'Gambia', 'code' => 'GM', 'prefix' => '+220'],

            ['name' => 'Georgia', 'code' => 'GE', 'prefix' => '+995'],

            ['name' => 'Germany', 'code' => 'DE', 'prefix' => '+49'],

            ['name' => 'Ghana', 'code' => 'GH', 'prefix' => '+233'],

            ['name' => 'Gibraltar', 'code' => 'GI', 'prefix' => '+350'],

            ['name' => 'Greece', 'code' => 'GR', 'prefix' => '+30'],

            ['name' => 'Greenland', 'code' => 'GL', 'prefix' => '+299'],

            ['name' => 'Grenada', 'code' => 'GD', 'prefix' => '+1'],

            ['name' => 'Guadeloupe', 'code' => 'GP', 'prefix' => '+590'],

            ['name' => 'Guam', 'code' => 'GU', 'prefix' => '+1'],

            ['name' => 'Guatemala', 'code' => 'GT', 'prefix' => '+502'],

            ['name' => 'Guernsey', 'code' => 'GG', 'prefix' => '+44'],

            ['name' => 'Guinea', 'code' => 'GN', 'prefix' => '+224'],

            ['name' => 'Guinea-Bissau', 'code' => 'GW', 'prefix' => '+245'],

            ['name' => 'Guyana', 'code' => 'GY', 'prefix' => '+592'],

            ['name' => 'Haiti', 'code' => 'HT', 'prefix' => '+509'],

            ['name' => 'Holy See (Vatican City State)', 'code' => 'VA', 'prefix' => '+379'],

            ['name' => 'Honduras', 'code' => 'HN', 'prefix' => '+504'],

            ['name' => 'Hong Kong', 'code' => 'HK', 'prefix' => '+852'],

            ['name' => 'Hungary', 'code' => 'HU', 'prefix' => '+36'],

            ['name' => 'Iceland', 'code' => 'IS', 'prefix' => '+354'],

            ['name' => 'India', 'code' => 'IN', 'prefix' => '+91'],

            ['name' => 'Indonesia', 'code' => 'ID', 'prefix' => '+62'],

            ['name' => 'Iran, Islamic Republic of', 'code' => 'IR', 'prefix' => '+98'],

            ['name' => 'Iraq', 'code' => 'IQ', 'prefix' => '+964'],

            ['name' => 'Ireland', 'code' => 'IE', 'prefix' => '+353'],

            ['name' => 'Isle of Man', 'code' => 'IM', 'prefix' => '+44'],

            ['name' => 'Israel', 'code' => 'IL', 'prefix' => '+972'],

            ['name' => 'Italy', 'code' => 'IT', 'prefix' => '+39'],

            ['name' => 'Jamaica', 'code' => 'JM', 'prefix' => '+1'],

            ['name' => 'Japan', 'code' => 'JP', 'prefix' => '+81'],

            ['name' => 'Jersey', 'code' => 'JE', 'prefix' => '+44'],

            ['name' => 'Jordan', 'code' => 'JO', 'prefix' => '+962'],

            ['name' => 'Kazakhstan', 'code' => 'KZ', 'prefix' => '+7'],

            ['name' => 'Kenya', 'code' => 'KE', 'prefix' => '+254'],

            ['name' => 'Kiribati', 'code' => 'KI', 'prefix' => '+686'],

            ['name' => 'Korea, Democratic People\'s Republic of', 'code' => 'KP', 'prefix' => '+850'],

            ['name' => 'Korea, Republic of', 'code' => 'KR', 'prefix' => '+82'],

            ['name' => 'Kuwait', 'code' => 'KW', 'prefix' => '+965'],

            ['name' => 'Kyrgyzstan', 'code' => 'KG', 'prefix' => '+996'],

            ['name' => 'Lao People\'s Democratic Republic', 'code' => 'LA', 'prefix' => '+856'],

            ['name' => 'Latvia', 'code' => 'LV', 'prefix' => '+371'],

            ['name' => 'Lebanon', 'code' => 'LB', 'prefix' => '+961'],

            ['name' => 'Lesotho', 'code' => 'LS', 'prefix' => '+266'],

            ['name' => 'Liberia', 'code' => 'LR', 'prefix' => '+231'],

            ['name' => 'Libya', 'code' => 'LY', 'prefix' => '+218'],

            ['name' => 'Liechtenstein', 'code' => 'LI', 'prefix' => '+423'],

            ['name' => 'Lithuania', 'code' => 'LT', 'prefix' => '+370'],

            ['name' => 'Luxembourg', 'code' => 'LU', 'prefix' => '+352'],

            ['name' => 'Macao', 'code' => 'MO', 'prefix' => '+853'],

            ['name' => 'Macedonia, the Former Yugoslav Republic of', 'code' => 'MK', 'prefix' => '+389'],

            ['name' => 'Madagascar', 'code' => 'MG', 'prefix' => '+261'],

            ['name' => 'Malawi', 'code' => 'MW', 'prefix' => '+265'],

            ['name' => 'Malaysia', 'code' => 'MY', 'prefix' => '+60'],

            ['name' => 'Maldives', 'code' => 'MV', 'prefix' => '+960'],

            ['name' => 'Mali', 'code' => 'ML', 'prefix' => '+223'],

            ['name' => 'Malta', 'code' => 'MT', 'prefix' => '+356'],

            ['name' => 'Marshall Islands', 'code' => 'MH', 'prefix' => '+692'],

            ['name' => 'Martinique', 'code' => 'MQ', 'prefix' => '+596'],

            ['name' => 'Mauritania', 'code' => 'MR', 'prefix' => '+222'],

            ['name' => 'Mauritius', 'code' => 'MU', 'prefix' => '+230'],

            ['name' => 'Mayotte', 'code' => 'YT', 'prefix' => '+262'],

            ['name' => 'Mexico', 'code' => 'MX', 'prefix' => '+52'],

            ['name' => 'Micronesia, Federated States of', 'code' => 'FM', 'prefix' => '+691'],

            ['name' => 'Moldova, Republic of', 'code' => 'MD', 'prefix' => '+373'],

            ['name' => 'Monaco', 'code' => 'MC', 'prefix' => '+377'],

            ['name' => 'Mongolia', 'code' => 'MN', 'prefix' => '+976'],

            ['name' => 'Montenegro', 'code' => 'ME', 'prefix' => '+382'],

            ['name' => 'Montserrat', 'code' => 'MS', 'prefix' => '+1'],

            ['name' => 'Morocco', 'code' => 'MA', 'prefix' => '+212'],

            ['name' => 'Mozambique', 'code' => 'MZ', 'prefix' => '+258'],

            ['name' => 'Myanmar', 'code' => 'MM', 'prefix' => '+95'],

            ['name' => 'Namibia', 'code' => 'NA', 'prefix' => '+264'],

            ['name' => 'Nauru', 'code' => 'NR', 'prefix' => '+674'],

            ['name' => 'Nepal', 'code' => 'NP', 'prefix' => '+977'],

            ['name' => 'Netherlands', 'code' => 'NL', 'prefix' => '+31'],

            ['name' => 'New Caledonia', 'code' => 'NC', 'prefix' => '+687'],

            ['name' => 'New Zealand', 'code' => 'NZ', 'prefix' => '+64'],

            ['name' => 'Nicaragua', 'code' => 'NI', 'prefix' => '+505'],

            ['name' => 'Niger', 'code' => 'NE', 'prefix' => '+227'],

            ['name' => 'Nigeria', 'code' => 'NG', 'prefix' => '+234'],

            ['name' => 'Niue', 'code' => 'NU', 'prefix' => '+683'],

            ['name' => 'Norfolk Island', 'code' => 'NF', 'prefix' => '+672'],

            ['name' => 'Northern Mariana Islands', 'code' => 'MP', 'prefix' => '+1'],

            ['name' => 'Norway', 'code' => 'NO', 'prefix' => '+47'],

            ['name' => 'Oman', 'code' => 'OM', 'prefix' => '+968'],

            ['name' => 'Pakistan', 'code' => 'PK', 'prefix' => '+92'],

            ['name' => 'Palau', 'code' => 'PW', 'prefix' => '+680'],

            ['name' => 'Palestine, State of', 'code' => 'PS', 'prefix' => '+970'],

            ['name' => 'Panama', 'code' => 'PA', 'prefix' => '+507'],

            ['name' => 'Papua New Guinea', 'code' => 'PG', 'prefix' => '+675'],

            ['name' => 'Paraguay', 'code' => 'PY', 'prefix' => '+595'],

            ['name' => 'Peru', 'code' => 'PE', 'prefix' => '+51'],

            ['name' => 'Philippines', 'code' => 'PH', 'prefix' => '+63'],

            ['name' => 'Pitcairn', 'code' => 'PN', 'prefix' => '+64'],

            ['name' => 'Poland', 'code' => 'PL', 'prefix' => '+48'],

            ['name' => 'Portugal', 'code' => 'PT', 'prefix' => '+351'],

            ['name' => 'Puerto Rico', 'code' => 'PR', 'prefix' => '+1'],

            ['name' => 'Qatar', 'code' => 'QA', 'prefix' => '+974'],

            ['name' => 'Réunion', 'code' => 'RE', 'prefix' => '+262'],

            ['name' => 'Romania', 'code' => 'RO', 'prefix' => '+40'],

            ['name' => 'Russian Federation', 'code' => 'RU', 'prefix' => '+7'],

            ['name' => 'Rwanda', 'code' => 'RW', 'prefix' => '+250'],

            ['name' => 'Saint Barthélemy', 'code' => 'BL', 'prefix' => '+590'],

            ['name' => 'Saint Helena, Ascension and Tristan da Cunha', 'code' => 'SH', 'prefix' => '+290'],

            ['name' => 'Saint Kitts and Nevis', 'code' => 'KN', 'prefix' => '+1'],

            ['name' => 'Saint Lucia', 'code' => 'LC', 'prefix' => '+1'],

            ['name' => 'Saint Martin (French part)', 'code' => 'MF', 'prefix' => '+590'],

            ['name' => 'Saint Pierre and Miquelon', 'code' => 'PM', 'prefix' => '+508'],

            ['name' => 'Saint Vincent and the Grenadines', 'code' => 'VC', 'prefix' => '+1'],

            ['name' => 'Samoa', 'code' => 'WS', 'prefix' => '+685'],

            ['name' => 'San Marino', 'code' => 'SM', 'prefix' => '+378'],

            ['name' => 'Sao Tome and Principe', 'code' => 'ST', 'prefix' => '+239'],

            ['name' => 'Saudi Arabia', 'code' => 'SA', 'prefix' => '+966'],

            ['name' => 'Senegal', 'code' => 'SN', 'prefix' => '+221'],

            ['name' => 'Serbia', 'code' => 'RS', 'prefix' => '+381'],

            ['name' => 'Seychelles', 'code' => 'SC', 'prefix' => '+248'],

            ['name' => 'Sierra Leone', 'code' => 'SL', 'prefix' => '+232'],

            ['name' => 'Singapore', 'code' => 'SG', 'prefix' => '+65'],

            ['name' => 'Sint Maarten (Dutch part)', 'code' => 'SX', 'prefix' => '+599'],

            ['name' => 'Slovakia', 'code' => 'SK', 'prefix' => '+421'],

            ['name' => 'Slovenia', 'code' => 'SI', 'prefix' => '+386'],

            ['name' => 'Solomon Islands', 'code' => 'SB', 'prefix' => '+677'],

            ['name' => 'Somalia', 'code' => 'SO', 'prefix' => '+252'],

            ['name' => 'South Africa', 'code' => 'ZA', 'prefix' => '+27'],

            ['name' => 'South Georgia and the South Sandwich Islands', 'code' => 'GS', 'prefix' => '+500'],

            ['name' => 'South Sudan', 'code' => 'SS', 'prefix' => '+211'],

            ['name' => 'Spain', 'code' => 'ES', 'prefix' => '+34'],

            ['name' => 'Sri Lanka', 'code' => 'LK', 'prefix' => '+94'],

            ['name' => 'Sudan', 'code' => 'SD', 'prefix' => '+249'],

            ['name' => 'Suriname', 'code' => 'SR', 'prefix' => '+597'],

            ['name' => 'Svalbard and Jan Mayen', 'code' => 'SJ', 'prefix' => '+47'],

            ['name' => 'Swaziland', 'code' => 'SZ', 'prefix' => '+268'],

            ['name' => 'Sweden', 'code' => 'SE', 'prefix' => '+46'],

            ['name' => 'Switzerland', 'code' => 'CH', 'prefix' => '+41'],

            ['name' => 'Syrian Arab Republic', 'code' => 'SY', 'prefix' => '+963'],

            ['name' => 'Taiwan', 'code' => 'TW', 'prefix' => '+886'],

            ['name' => 'Tajikistan', 'code' => 'TJ', 'prefix' => '+992'],

            ['name' => 'Tanzania, United Republic of', 'code' => 'TZ', 'prefix' => '+255'],

            ['name' => 'Thailand', 'code' => 'TH', 'prefix' => '+66'],

            ['name' => 'Timor-Leste', 'code' => 'TL', 'prefix' => '+670'],

            ['name' => 'Togo', 'code' => 'TG', 'prefix' => '+228'],

            ['name' => 'Tokelau', 'code' => 'TK', 'prefix' => '+690'],

            ['name' => 'Tonga', 'code' => 'TO', 'prefix' => '+676'],

            ['name' => 'Trinidad and Tobago', 'code' => 'TT', 'prefix' => '+868'],

            ['name' => 'Tunisia', 'code' => 'TN', 'prefix' => '+216'],

            ['name' => 'Turkey', 'code' => 'TR', 'prefix' => '+90'],

            ['name' => 'Turkmenistan', 'code' => 'TM', 'prefix' => '+993'],

            ['name' => 'Turks and Caicos Islands', 'code' => 'TC', 'prefix' => '+1'],

            ['name' => 'Tuvalu', 'code' => 'TV', 'prefix' => '+688'],

            ['name' => 'Uganda', 'code' => 'UG', 'prefix' => '+256'],

            ['name' => 'Ukraine', 'code' => 'UA', 'prefix' => '+380'],

            ['name' => 'United Arab Emirates', 'code' => 'AE', 'prefix' => '+971'],

            ['name' => 'United Kingdom', 'code' => 'GB', 'prefix' => '+44'],

            ['name' => 'United States', 'code' => 'US', 'prefix' => '+1'],

            ['name' => 'United States Minor Outlying Islands', 'code' => 'UM', 'prefix' => '+246'],

            ['name' => 'Uruguay', 'code' => 'UY', 'prefix' => '+598'],

            ['name' => 'Uzbekistan', 'code' => 'UZ', 'prefix' => '+998'],

            ['name' => 'Vanuatu', 'code' => 'VU', 'prefix' => '+678'],

            ['name' => 'Venezuela, Bolivarian Republic of', 'code' => 'VE', 'prefix' => '+58'],

            ['name' => 'Vietnam', 'code' => 'VN', 'prefix' => '+84'],

            ['name' => 'Virgin Islands, British', 'code' => 'VG', 'prefix' => '+1'],

            ['name' => 'Virgin Islands, U.S.', 'code' => 'VI', 'prefix' => '+1'],

            ['name' => 'Wallis and Futuna', 'code' => 'WF', 'prefix' => '+681'],

            ['name' => 'Western Sahara', 'code' => 'EH', 'prefix' => '+212'],

            ['name' => 'Yemen', 'code' => 'YE', 'prefix' => '+967'],

            ['name' => 'Zambia', 'code' => 'ZM', 'prefix' => '+260'],

            ['name' => 'Zimbabwe', 'code' => 'ZW', 'prefix' => '+263'],
        ];

        $categories=Category::all();
        $countries = Country::all();

        $user = $this->vendorRepository->findWithoutFail($id);
        unset($user->password);
        $html = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->vendorRepository->model());
        $hasCustomField = in_array($this->vendorRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        if (empty($user)) {
            Flash::success(trans('lang.user_not_found'));

            return redirect(route('users.index'));
        }

        if (!empty($user->cities->id)) {
            $cities = City::where('country_id', $user->cities->country_id)->get();
        } else
            $cities = [];


            Mapper::map(
                $user->coordinates != null ? $user->coordinates->latitude : 36.216667 ,
                $user->coordinates != null ? $user->coordinates->longitude : 37.166668,
                    [
                        'zoom'         => 8,
                        'draggable'    => true,
                        'marker'       => true,
                        'markers'      => ['title' => 'My Location', 'animation' => 'BOUNCE'],
                        'eventDragEnd' =>  '
                                            document.getElementById("latitude").value = event.latLng.lat(),
                                            document.getElementById("longitude").value = event.latLng.lng()',
                    ]
                );

        $style = "";
        return view('settings.vendors.edit')
            ->with('user', $user)->with("role", $role)
            ->with("rolesSelected", $rolesSelected)
            ->with("customFields", $html)
            ->with("style", $style)
            ->with("categories", $categories)
            ->with("countries", $countries)
            ->with("cities", $cities)
            ->with('countries_codes', $countries_codes);


    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('vendors.destroy')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }
        $user = $this->vendorRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::success(trans('lang.user_not_found'));

            return redirect(route('vendors.index'));
        }

        if ($user->balance_id != null) {
            Balance::find($user->balance_id)->delete();
        }

        try {
            unlink(public_path('storage/Avatar') . '/' . $user->avatar);
        } catch (\Exception $e) {
        }

        $this->vendorRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('vendors.index'));
    }

    public function DeleteGallerySpImage(Request $request) {

        if (!auth()->user()->hasPermissionTo('vendorsGallery.destroy')) {
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $gallery = Gallery::find($request->id);

        if (empty($gallery)) {
            Flash::success(trans('lang.image_not_found_in_gallery'));

            return redirect()->back();
        }

        $gallery->delete();

        Flash::success(trans('lang.delete_operation'));

        return redirect()->back();
    }
}
