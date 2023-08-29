<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Reply;
use App\Models\Posting;
use App\Models\Employee;
use App\Models\FollowUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;


class PostingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function dashboard(){
    //     $user = JWTAuth::parseToken()->authenticate();
    //     $user->posting;
    //     return $this->showData($user);
    // }
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $user->followUser->pluck('following_id');
        $data = Reply::join('postings', 'replies.id_postings','=','postings.id')
            ->join('employees', 'employees.id_user','=','postings.id_user')
            ->join('follow_users', 'follow_users.id_user','=','postings.id_user')
            ->where('follow_users.follow_status','!=',1)
            ->where('follow_users.id_user','!=',$user->id)
            ->where('postings.id_user','!=',$user->id)
            ->where('replies.toAnswer_posting','=',null);
            // ->where('postings.id','=',1299);
            // ->get();
            // ->get();
        
        // Following User Posts
        // $userFollowing = FollowUser::where('id_user', '=', $user->id)->get();
        // for ($i = 0; $i < $userFollowing->count(); $i++) {
        //     $data = $data->orWhere('postings.id_user', '=', $userFollowing[$i]->following_id)->where('replies.toAnswer_posting', '=', null);
        // }
        // // Fetching data from query
        $data = $data->inRandomOrder()->get();


        $data = $this->cekReplyData($data);
        // return $data;
        return $this->showAll($data);
    }

    public function detailPost($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $user->followUser->pluck('following_id');
        $data = Reply::join('postings', 'replies.id_postings','=','postings.id')
            ->join('employees', 'employees.id_user','=','postings.id_user')
            ->join('follow_users', 'follow_users.id_user','=','postings.id_user')
            // ->where('follow_users.follow_status','!=',1)
            // ->where('follow_users.id_user','!=',$user->id)
            ->where('postings.id','=',$id)
            // ->where('replies.toAnswer_posting','=',null);
            // ->where('postings.id','=',1299);
            ->get();
            // ->get();
        
        // Following User Posts
        // $userFollowing = FollowUser::where('id_user', '=', $user->id)->get();
        // for ($i = 0; $i < $userFollowing->count(); $i++) {
        //     $data = $data->orWhere('postings.id_user', '=', $userFollowing[$i]->following_id)->where('replies.toAnswer_posting', '=', null);
        // }
        // // Fetching data from query
        // $data = $data->inRandomOrder()->get();


        $data = $this->cekReplyData($data);
        // return $data;
        return $this->showAll($data);
        return $data;
    }

    private function cekReplyData($data)
    {
        $lariknya = array();
        for ($i = 0; $i < count($data); $i++) {
            $appendData = Reply::join('postings', 'replies.id_postings', '=', 'postings.id')->where('replies.toAnswer_posting', '=', $data[$i]->id_postings)->orderBy('postings.updated_at', 'desc')->get();
            $appendData = $this->cekReplyData($appendData);
            array_push($lariknya, $appendData);
            $data[$i]->repliedData = $appendData;
        }
        return $data;
    }
    public function indexFollowing()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $user->followUser->pluck('following_id');
        $data = Reply::join('postings', 'replies.id_postings','=','postings.id')
            ->join('employees', 'employees.id_user','=','postings.id_user')
            ->join('follow_users', 'follow_users.id_user','=','postings.id_user')
            ->where('follow_users.follow_status','=',1) //following
            ->where('follow_users.id_user','!=',$user->id)
            ->where('postings.id_user','!=',$user->id)
            ->where('replies.toAnswer_posting','=',null);
            // ->where('postings.id','=',1299);
            // ->get();
            // ->get();
        
        // Following User Posts
        // $userFollowing = FollowUser::where('id_user', '=', $user->id)->get();
        // for ($i = 0; $i < $userFollowing->count(); $i++) {
        //     $data = $data->orWhere('postings.id_user', '=', $userFollowing[$i]->following_id)->where('replies.toAnswer_posting', '=', null);
        // }
        // // Fetching data from query
        $data = $data->inRandomOrder()->get();


        $data = $this->cekReplyData($data);
        // return $data;
        return $this->showAll($data);
    }

    public function profile($id)
    {
        // $user=Employee::where('id_user', $id);
        $user=DB::table('employees')
            ->where('employees.id_user', $id)
            ->get();
        return $user;
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
            'title' ,
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            // $duplikasi = Posting::where('id_user', '=', $user->employees->id)->get();

            // if ($duplikasi->count() == 0) {
            $posting = new Posting();
            $posting->id_user = $user->employees->id;
            $posting->id_credential = $user->employees->id;
            // $posting->id_credential = $user->employees->id;
            $posting->title = $request->title;
            $posting->description = $request->description;
            $posting->save();

            // Merit System
            # Code here...
            // } else {
            //     return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            // }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function show(Posting $posting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function edit(Posting $posting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Posting $posting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posting $posting)
    {
        //
    }
}
