<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

use App\DataTables\LanguageDataTable;
use App\Repositories\LanguageRepository;
use Flash;


class LanguageController extends Controller
{
    /** @var  SubCategoriesRepository */
    private $languageRepository;

    public function __construct(LanguageRepository $languageRepo)
    {
        parent::__construct();
        $this->languageRepository = $languageRepo;
      

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LanguageDataTable $languageDataTable)
    {
        return $languageDataTable->render('languages.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('languages.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
  
        try {
            $language = $this->languageRepository->create($input);
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('language.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(language $language)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(language $language)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, language $language)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(language $language)
    {
        //
    }
}
