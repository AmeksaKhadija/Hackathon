<?php

namespace App\Http\Controllers;

use App\Models\Jury;
use App\Http\Requests\StoreJuryRequest;
// use App\Http\Requests\UpdateJuryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JuryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jury = Jury::with(['projets', 'member_jury'])->get();
        if ($jury->isEmpty()) {
            return response()->json([
                'message' => 'aucun editions trouvée'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $jury
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
     * @param  \App\Http\Requests\StoreJuryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jury = Jury::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jury créé avec succès',
            'data' => $jury
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jury  $jury
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jury = Jury::with(['projets', 'member_jury'])->find($id);
        
        if (!$jury) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jury not found '
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $jury
        ]);
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
    public function update(Request $request,$id)
    {
        $jury = Jury::find($id);
        
        if (!$jury) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jury non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jury->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jury mis à jour avec succès',
            'data' => $jury
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jury  $jury
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jury = Jury::find($id);
        
        if (!$jury) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jury non trouvé'
            ], 404);
        }

        if ($jury->member_jury()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de supprimer ce jury car il contient des membres'
            ], 422);
        }

        $jury->projets()->detach();

        $jury->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jury supprimé avec succès'
        ]);
    }


    // assigner un projet à un jury
    public function assignProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jury_id' => 'required|exists:jurys,id',
            'projet_id' => 'required|exists:projets,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jury = Jury::find($request->jury_id);
        
        if ($jury->projets()->where('projet_id', $request->projet_id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce projet est déjà assigné à ce jury'
            ], 422);
        }

        $jury->projets()->attach($request->projet_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Projet assigné au jury avec succès'
        ]);
    }

    public function getJuryProjects($id)
    {
        $jury = Jury::find($id);
        
        if (!$jury) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jury not found'
            ], 404);
        }
        
        $projects = $jury->projets()->with('equipe')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $projects
        ]);
    }
}
