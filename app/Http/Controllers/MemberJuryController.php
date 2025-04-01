<?php

namespace App\Http\Controllers;

use App\Models\Member_jury;
// use App\Http\Requests\StoreMember_juryRequest;
// use App\Http\Requests\UpdateMember_juryRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberJuryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member_jury::with('jury')->get();
        if ($members->isEmpty()) {
            return response()->json([
                'message' => 'aucun membre trouvée'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $members
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
     * @param  \App\Http\Requests\StoreMember_juryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jury_id' => 'required|exists:jurys,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:member_jurys,random_email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $randomPassword = Str::random(10);

        $member = Member_jury::create([
            'jury_id' => $request->jury_id,
            'name' => $request->name,
            'random_email' => $request->email,
            'random_password' => Hash::make($randomPassword)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Membre du jury créé avec succès',
            'data' => $member,
            'password' => $randomPassword
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member_jury  $member_jury
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member_jury::with('jury')->find($id);

        if (!$member) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membre du jury non trouvé'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $member
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member_jury  $member_jury
     * @return \Illuminate\Http\Response
     */
    public function edit(Member_jury $member_jury)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMember_juryRequest  $request
     * @param  \App\Models\Member_jury  $member_jury
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $member = Member_jury::find($id);

        if (!$member) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membre du jury not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jury_id' => 'exists:jurys,id',
            'name' => 'string|max:255',
            'email' => 'email|unique:member_jurys,random_email,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [];
        if ($request->has('jury_id')) $updateData['jury_id'] = $request->jury_id;
        if ($request->has('name')) $updateData['name'] = $request->name;
        if ($request->has('email')) $updateData['random_email'] = $request->email;

        $member->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Membre du jury mis à jour avec succès',
            'data' => $member
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member_jury  $member_jury
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member_jury::find($id);

        if (!$member) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membre du jury non trouvé'
            ], 404);
        }

        $member->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Membre du jury supprimé avec succès'
        ]);
    }

    public function getMembersByJury($juryId)
    {
        $members = Member_jury::where('jury_id', $juryId)->get();
        
        if (!$members) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membre du jury non trouvé'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $members
        ]);
    }
}
