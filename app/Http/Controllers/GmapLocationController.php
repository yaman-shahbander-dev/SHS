<?php

namespace App\Http\Controllers;

use App\Models\GmapLocation;
use Illuminate\Http\Request;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Support\Facades\Route;

class GmapLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GmapLocation  $gmapLocation
     * @return \Illuminate\Http\Response
     */
    public function show(GmapLocation $gmapLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GmapLocation  $gmapLocation
     * @return \Illuminate\Http\Response
     */
    public function edit(GmapLocation $gmapLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GmapLocation  $gmapLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GmapLocation $gmapLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GmapLocation  $gmapLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(GmapLocation $gmapLocation)
    {
        //
    }
}
