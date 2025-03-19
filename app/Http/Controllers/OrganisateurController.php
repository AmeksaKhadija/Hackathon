<?php

namespace App\Http\Controllers;

use App\Models\Organisateur;
use App\Http\Requests\StoreOrganisateurRequest;
use App\Http\Requests\UpdateOrganisateurRequest;

class OrganisateurController extends Controller
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
     * @param  \App\Http\Requests\StoreOrganisateurRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganisateurRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organisateur  $organisateur
     * @return \Illuminate\Http\Response
     */
    public function show(Organisateur $organisateur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organisateur  $organisateur
     * @return \Illuminate\Http\Response
     */
    public function edit(Organisateur $organisateur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrganisateurRequest  $request
     * @param  \App\Models\Organisateur  $organisateur
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrganisateurRequest $request, Organisateur $organisateur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organisateur  $organisateur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organisateur $organisateur)
    {
        //
    }
}
