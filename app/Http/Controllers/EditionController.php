<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use App\Http\Requests\StoreEditionRequest;
use App\Http\Requests\UpdateEditionRequest;
use App\Models\Statistique;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
// use Exception;

class EditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $editions = Edition::with(['statistique', 'equipes', 'organisateurs'])->get();

        if ($editions->isEmpty()) {
            return response()->json([
                'message' => 'aucun editions trouvée'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $editions
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
     * @param  \App\Http\Requests\StoreEditionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'theme' => 'required|string|max:255',
                'regle' => 'required|string',
                'lieu' => 'required|string|max:255',
                'date' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $edition = Edition::create($request->all());

            // Create associated statistics
            Statistique::create([
                'edition_id' => $edition->id,
                'nb_equipes' => 0,
                'nb_participants' => 0
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Edition créée avec succès',
                'data' => $edition
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Erreur lors de création du edition",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $edition = Edition::with(['statistique', 'equipes', 'organisateurs'])->find($id);

        if (!$edition) {
            return response()->json([
                'status' => 'error',
                'message' => 'Edition non trouvée'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $edition
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function edit(Edition $edition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEditionRequest  $request
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $edition = Edition::find($id);

        if (!$edition) {
            return response()->json([
                'status' => 'error',
                'message' => 'Edition non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'theme' => 'string|max:255',
            'regle' => 'string',
            'lieu' => 'string|max:255',
            'date' => 'date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $edition->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Edition mise à jour avec succès',
            'data' => $edition
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Edition  $edition
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $edition = Edition::find($id);

        if (!$edition) {
            return response()->json([
                'status' => 'error',
                'message' => 'Edition non trouvée'
            ], 404);
        }

        if ($edition->equipes()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de supprimer cette édition car elle contient des équipes'
            ], 422);
        }

        if ($edition->statistique) {
            $edition->statistique->delete();
        }

        $edition->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Edition supprimée avec succès'
        ]);
    }

    public function getEditionTeams($id)
    {
        $edition = Edition::find($id);

        if (!$edition) {
            return response()->json([
                'status' => 'error',
                'message' => 'Edition non trouvée'
            ], 404);
        }

        $teams = $edition->equipes()->with('participants')->get();

        return response()->json([
            'status' => 'success',
            'data' => $teams
        ]);
    }
}
