<?php

namespace App\Http\Controllers;

use App\Models\Jury;
use App\Http\Requests\StoreJuryRequest;
use App\Http\Requests\UpdateJuryRequest;

class JuryController extends Controller
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
     * @param  \App\Http\Requests\StoreJuryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJuryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jury  $jury
     * @return \Illuminate\Http\Response
     */
    public function show(Jury $jury)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jury  $jury
     * @return \Illuminate\Http\Response
     */
    public function edit(Jury $jury)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJuryRequest  $request
     * @param  \App\Models\Jury  $jury
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJuryRequest $request, Jury $jury)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jury  $jury
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jury $jury)
    {
        //
    }
}
