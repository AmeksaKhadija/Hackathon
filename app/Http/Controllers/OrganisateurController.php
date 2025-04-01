<?php

namespace App\Http\Controllers;

use App\Models\Organisateur;
use App\Models\Role;
use App\Models\User;
// use App\Http\Requests\StoreOrganisateurRequest;
// use App\Http\Requests\UpdateOrganisateurRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class OrganisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organisateurs = Organisateur::with(['user', 'edition'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $organisateurs
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
     * @param  \App\Http\Requests\StoreOrganisateurRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'edition_id' => 'required|exists:editions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $existingOrganisateur = Organisateur::where('user_id', $request->user_id)
            ->where('edition_id', $request->edition_id)
            ->first();
            
        if ($existingOrganisateur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cet utilisateur est déjà organisateur pour cette édition'
            ], 422);
        }

        $organisateur = Organisateur::create($request->all());

        // Assign organisateur role to user if needed
        $user = User::find($request->user_id);
        $organisateurRole =Role::where('name', 'organisateur')->first();
        
        if ($organisateurRole && !$user->roles()->where('role_id', $organisateurRole->id)->exists()) {
            $user->roles()->attach($organisateurRole->id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Organisateur créé avec succès',
            'data' => $organisateur
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organisateur  $organisateur
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $organisateur = Organisateur::with(['user', 'edition'])->find($id);
        
        if (!$organisateur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Organisateur non trouvé'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $organisateur
        ]);
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
    public function update(Request $request, $id)
    {
        $organisateur = Organisateur::find($id);
        
        if (!$organisateur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Organisateur non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'edition_id' => 'exists:editions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $organisateur->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Organisateur mis à jour avec succès',
            'data' => $organisateur
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organisateur  $organisateur
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $organisateur = Organisateur::find($id);
        
        if (!$organisateur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Organisateur non trouvé'
            ], 404);
        }

        $organisateur->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Organisateur supprimé avec succès'
        ]);
    }

    public function getOrganisateursByEdition($editionId)
    {
        $organisateurs = Organisateur::with('user')
            ->where('edition_id', $editionId)
            ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $organisateurs
        ]);
    }
}
