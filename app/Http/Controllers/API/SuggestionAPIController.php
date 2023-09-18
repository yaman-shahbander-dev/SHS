<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use App\Repositories\SuggestionRepository;
use App\Repositories\UserRepository;
use App\Repositories\RestaurantRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class SuggestionAPIController extends Controller
{
    private $suggestionRepository;
   
    private $userRepository;
   
    private $restaurantRepository;
    
    
    public function __construct(SuggestionRepository $suggestionRepo, UserRepository $userRepo , RestaurantRepository $restaurantRepo)
    {
        $this->suggestionRepository = $suggestionRepo;
        $this->userRepository = $userRepo;
        $this->restaurantRepository = $restaurantRepo;
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        //dd($input);
        try{
             $suggestion = $this->suggestionRepository->create(
                $request->only('user_id', 'restaurant_id','msg')
            );
        }
        
        catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($suggestion->toArray(), __('lang.saved_successfully', ['operator' => 'Suggestion']));
    }
    
}
