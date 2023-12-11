<?php

namespace App\Http\Controllers\Api;

use App\Models\Reply;
use App\Models\Posting;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\ApiController;
use App\Models\Credential;
use Illuminate\Support\Facades\Validator;

class ReplyController extends ApiController
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

        // Validation Requests
        $validator = Validator::make($request->all(), [
            'id_postings' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            // $duplikasi = Posting::where('id_user', '=', $user->employees->id)->get();
            // $credential = Credential::where('id_employee', '=', $user->employees->id)
            // ->where('type', '=', 0)->first();

            // if ($duplikasi->count() == 0) {
            $posting = new Posting();
            $posting->id_user = $user->employees->id;
            // $posting->id_credential = $credential->id;
            // $posting->id_credential = $user->employees->id;
            $posting->title = $request->title;
            $posting->description = $request->description;
            $posting->save();

            $reply = new Reply();
            $reply->id_postings = $posting->id;
            $reply->toAnswer_posting = $request->id_postings;
            $reply->save();
            return $reply;
        }
    }
    public function showComment()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $coment = Posting::where('id_user', '=', $user->id)
            ->join('replies', 'replies.id_postings', '=', 'postings.id')
            ->where('replies.toAnswer_posting', '!=', null)
            // ->orderBy('postings.created_at', 'desc')
            ->get();
        // Menggabungkan postingan lain sesuai dengan 'toAnswer_posting'
        foreach ($coment as $comment) {
            $comment->toAnswer_posting = Posting::where('postings.id', '=', $comment->toAnswer_posting)
                ->join('employees', 'employees.id_user', '=', 'postings.id_user')
                ->select('postings.*', 'employees.nickname', 'employees.company')
                ->get();
        }
        // $coment = $this->cekReplyData($coment);
        return $coment;
    }
    private function cekReplyData($data)
    {
        $lariknya = array();
        for ($i = 0; $i < count($data); $i++) {
            // $appendData = Reply::join('postings', 'replies.id_postings', '=', 'postings.id')->where('replies.toAnswer_posting', '=', $data[$i]->id_postings)->orderBy('postings.updated_at', 'desc')->get();
            $appendData = Posting::where('postings.id', '=', $data[$i]->toAnswer_posting)
                ->join('replies', 'replies.id_postings', '=', 'postings.id')
                ->join('employees', 'employees.id_user', '=', 'postings.id_user')
                ->select('replies.toAnswer_posting', 'postings.*', 'employees.nickname', 'employees.company')
                // ->select('postings.*', 'employees.nickname', 'employees.company')
                ->get();
            $appendData = $this->cekReplyData($appendData);
            array_push($lariknya, $appendData);
            $data[$i]->replied = $appendData;
        }
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Reply::where('id_postings', '=', $id)
            ->delete();
        Posting::where('id', '=', $id)
            ->delete();
    }
}
