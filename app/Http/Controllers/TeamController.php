<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use App\Models\Equipe;
use App\Models\Projet;
use App\Models\Statistique;
// use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipes = Equipe::with(['edition', 'participants', 'projet'])->get();

        if ($equipes->isEmpty()) {
            return response()->json([
                'message' => 'aucun team trouvée'
            ]);
        }
        if ($equipes->isEmpty()) {
            return response()->json([
                'message' => 'aucun equipe trouvée'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $equipes
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edition_id' => 'required|exists:editions,id',
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $equipe = Equipe::create($request->all());

        // Update statistics
        $statistique = Statistique::where('edition_id', $request->edition_id)->first();
        if ($statistique) {
            $statistique->increment('nb_equipes');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Equipe créée avec succès',
            'data' => $equipe
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $equipe = Equipe::with(['edition', 'participants', 'projet', 'messages'])->find($id);

        if (!$equipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Equipe non trouvée'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $equipe
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $equipe = Equipe::find($id);

        if (!$equipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Equipe non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'edition_id' => 'exists:editions,id',
            'name' => 'string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('edition_id') && $request->edition_id != $equipe->edition_id) {

            $oldStats = Statistique::where('edition_id', $equipe->edition_id)->first();
            if ($oldStats) {
                $oldStats->decrement('nb_equipes');
                $oldStats->decrement('nb_participants', $equipe->participants()->count());
            }

            $newStats = Statistique::where('edition_id', $request->edition_id)->first();
            if ($newStats) {
                $newStats->increment('nb_equipes');
                $newStats->increment('nb_participants', $equipe->participants()->count());
            }
        }

        $equipe->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Equipe mise à jour avec succès',
            'data' => $equipe
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipe = Equipe::find($id);
        
        if (!$equipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Equipe non trouvée'
            ], 404);
        }

        $statistique = Statistique::where('edition_id', $equipe->edition_id)->first();
        if ($statistique) {
            $statistique->decrement('nb_equipes');
            $statistique->decrement('nb_participants', $equipe->participants()->count());
        }

        if ($equipe->projet) {
            $equipe->projet->delete();
        }

        $equipe->messages()->delete();

        $equipe->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Equipe supprimée avec succès'
        ]);
    }


    public function getTeamParticipants($id)
    {
        $equipe = Equipe::find($id);
        
        if (!$equipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Equipe non trouvée'
            ], 404);
        }
        
        $participants = $equipe->participants()->with('user')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $participants
        ]);
    }

    public function getTeamProject($id)
    {
        $equipe = Equipe::find($id);
        
        if(!$equipe){
            return response()->json([
                'status' => 'error',
                'message' => 'Equipe non trouvée'
            ], 404);
        }
        
        $projet = $equipe->projet;
        
        if(!$projet){
            return response()->json([
                'status' => 'error',
                'message' => 'Cette équipe n\'a pas encore de projet'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $projet
        ]);
    }
}
