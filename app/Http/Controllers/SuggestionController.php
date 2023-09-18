<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\SuggestionsDataTable;
use App\Repositories\RestaurantRepository;
use App\Repositories\UserRepository;
use App\Repositories\SuggestionRepository;

class SuggestionController extends Controller
{
    private $suggestionRepository;
    private $userRepository;
    private $restaurantRepository;


    public function __construct(SuggestionRepository $suggestionRepo, UserRepository $userRepo, RestaurantRepository $restaurantRepo)
    {
        parent::__construct();
        $this->suggestionRepository = $suggestionRepo;
        $this->userRepository = $userRepo;
        $this->restaurantRepository = $restaurantRepo;

    }
    public function index(SuggestionsDataTable $suggestionsDataTable)
    {
        return $suggestionsDataTable->render('suggestions.index');
    }
}
