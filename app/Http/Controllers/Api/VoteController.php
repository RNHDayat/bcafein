<?php

namespace App\Http\Controllers\Api;

use App\Models\Vote;
use App\Models\Posting;
use App\Models\Employee;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

class VoteController extends ApiController
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
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $vote = $validator = Validator::make($request->all(), [
            'id_postings' => 'required',
            'vote_status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        // Lakukan operasi updateOrCreate
        $vote = Vote::updateOrCreate(
            [
                'id_user' => $user->id,
                'id_postings' => $request->id_postings,
            ],
            [
                'id_user' => $user->id,
                'id_postings' => $request->id_postings,
                'vote_status' => $request->vote_status,
            ]
        );
        return response()->json(['message' => 'Vote updated successfully', 'data' => $vote], 200);
    }
    public function rank()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees;
        $rank = Employee::all();
        $combine = $rank->map(function ($item) {
            $upvote = Posting::join('votes', 'votes.id_postings', 'postings.id')
                ->where('postings.id_user', '=', $item->id_user)
                ->where('votes.vote_status', '=', 1)
                ->get();
            $downvote = Posting::join('votes', 'votes.id_postings', 'postings.id')
                ->where('postings.id_user', '=', $item->id_user)
                ->where('votes.vote_status', '=', 2)
                ->get();

            $item->upvote = count($upvote);
            $item->downvote = count($downvote);
            $item->point = count($upvote) * 5 - count($downvote);
            return $item;
        });
        // Sort $combine based on the 'total' attribute in descending order
        $combine = $combine->sortByDesc('point');

        // Add rank to each item based on the sorted order
        $combine->values()->each(function ($item, $key) {
            $item->rank = $key + 1;
        });

        return response()->json(['data' => $combine->values()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
