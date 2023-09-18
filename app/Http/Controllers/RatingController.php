<?php

namespace App\Http\Controllers;

use App\DataTables\RatingDataTable;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\VendorRepository;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    private $vendorRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    private $uploadRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    public function __construct(VendorRepository $vendorRepo, RoleRepository $roleRepo, UploadRepository $uploadRepo,
                                CustomFieldRepository $customFieldRepo)
    {
        parent::__construct();
        $this->vendorRepository = $vendorRepo;
        $this->roleRepository = $roleRepo;
        $this->uploadRepository = $uploadRepo;
        $this->customFieldRepository = $customFieldRepo;
    }

    public function index(RatingDataTable $ratingDataTable)
    {

        return $ratingDataTable->render('ratings.index');
    }
}
