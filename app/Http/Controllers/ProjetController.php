<?php

namespace App\Http\Controllers;

use App\Models\Projet;
// use App\Http\Requests\StoreProjetRequest;
// use App\Http\Requests\UpdateProjetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projets = Projet::with(['equipe', 'jury'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $projets
        ]);
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
     * @param  \App\Http\Requests\StoreProjetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'equipe_id' => 'required|exists:equipes,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'repository_url' => 'nullable|url',
            'demo_url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $existingProject = Projet::where('equipe_id', $request->equipe_id)->first();
        if ($existingProject) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette équipe a déjà un projet'
            ], 422);
        }

        $projet = Projet::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Projet créé avec succès',
            'data' => $projet
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $projet = Projet::with(['equipe', 'jury'])->find($id);

        if (!$projet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Projet non trouvé'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $projet
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function edit(Projet $projet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjetRequest  $request
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $projet = Projet::find($id);

        if (!$projet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Projet non trouvé'
            ], 404);
        }

        // Detach from juries
        $projet->jury()->detach();

        $projet->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Projet supprimé avec succès'
        ]);
    }
}
