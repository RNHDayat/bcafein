<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Vote;
use ReflectionClass;
use App\Models\Reply;
use App\Models\Posting;
use App\Models\Employee;
use App\Models\Credential;
use App\Models\FollowUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\ApiController;

use function PHPUnit\Framework\isEmpty;
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
        // $user->followUser->pluck('following_id');
        // $data = Reply::join('postings', 'replies.id_postings', '=', 'postings.id')
        //     ->join('employees', 'employees.id_user', '=', 'postings.id_user')
        //     ->join('follow_users', 'follow_users.id_user', '=', 'postings.id_user')
        //     // ->where('postings.id_knowField', '=', null)
        //     ->where('follow_users.follow_status', '!=', 1)
        //     ->where('follow_users.id_user', '!=', $user->id)
        //     ->where('postings.id_user', '!=', $user->id)
        //     ->where('replies.toAnswer_posting', '=', null) //bukan sebuah komentar
        //     ->select('postings.*', 'replies.*', 'follow_users.*', 'employees.nickname', 'employees.fullname', 'employees.company')
        //     ->inRandomOrder()->get();
        $data = Posting::leftJoin('replies', 'replies.id_postings', '=', 'postings.id')
            ->leftJoin('employees', 'employees.id_user', '=', 'postings.id_user')
            ->where('postings.id_user', '!=', $user->id) //bukan yg login
            ->where('replies.toAnswer_posting', '=', null) //bukan sebuah komentar
            ->select('postings.*', 'employees.nickname', 'employees.fullname', 'employees.company')
            ->get();
        //apakah ngefollow?
        $cekFollow = FollowUser::where('id_user', '=', $user->id)->get();
        $combinedData = $data->map(function ($item) use ($cekFollow) {
            $follow = $cekFollow->where('following_id', $item->id_user)->first();
            if ($follow) {
                $item->follow_status = $follow->follow_status;
            } else {
                $item->follow_status = 0;
            }
            return $item;
        });
        //apakah voting?
        $combinedData = $data->map(function ($item) use ($user) {
            $vote = Vote::where('id_user', '=', $user->id)
                ->where('id_postings', $item->id)
                ->first();
            $up = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '1')
                ->get();
            $down = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '2')
                ->get();
            $item->upvote = count($up);
            $item->downvote = count($down);
            $item->point = count($up) * 5 + count($down);

            if ($vote) {
                $item->vote_status = $vote->vote_status;
            } else {
                $item->vote_status = null;
            }
            return $item;
        });
        // Filter data with follow_status != 1
        $combinedData = $combinedData->filter(function ($item) {
            return $item->follow_status != 1;
        });
        return $this->showAll($combinedData);
    }

    public function likeDetailPost($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        //apakah voting?
        $vote = Vote::where('id_user', $user->id)->where('id_postings', $id)
            ->get();
        // $vote = $cekVoting->where('id_postings', $id)->first();
        // if ($vote) {
        //     $cekVoting->vote_status = $vote->vote_status;
        // } else {
        //     $cekVoting->vote_status = null;
        // }

        // $combinedData = $cekVoting->map(function ($item) {
        // $vote = Vote::where('id_postings', $id)->get();
        if (count($vote) == 0) {
            // Create an empty map with field names as keys
            $data = [
                'id_user' => null,
                'id_postings' => null,
                'vote_status' => null,
                'upvote' => null,
                'downvote' => null,
            ];

            // Return the map as JSON
            return response()->json(['data' => $data]);
        } else {

            $up = Vote::where('id_postings', $id)
                ->where('vote_status', '1')
                ->get();
            $down = Vote::where('id_postings', $id)
                ->where('vote_status', '2')
                ->get();
            $vote[0]->upvote = count($up);
            $vote[0]->downvote = count($down);
            return $this->showData($vote[0]);
        }

        //     return $item;
        // });
    }
    public function commentDetailPost($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $data = Posting::where('postings.id', '=', $id)
            ->get();
        $data = $this->cekReplyData($data);
        // for($i=0;$i<count($data);$i++){
        //     $data[$i]->repliedData[$i]->toAnswer_posting=8;
        //     // return $data[$i];
        // }
        return $data;
    }
    public function deleteCommentDetailPost($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $data = Posting::where('postings.id', '=', $id)
            ->get();
        $data = $this->cekReplyData($data);
        $data = $this->deleteReplies($data);

        return $data;
    }
    private function deleteReplies($replies)
    {
        foreach ($replies as $reply) {
            if (!empty($reply->repliedData)) {
                $this->deleteReplies($reply->repliedData);
            }

            $ada = Reply::where('id_postings', $reply->id)->first();
            if ($ada) {
                $ada->delete();
            }
            Posting::where('id', $reply->id)->delete();
        }
        return response()->json(['msg' => 'Postingan atau komentar berhasil dihapus'], 200);
    }


    private function cekReplyData($data)
    {
        $lariknya = array();
        for ($i = 0; $i < count($data); $i++) {
            $appendData = Reply::join('postings', 'replies.id_postings', '=', 'postings.id')
                ->join('employees', 'employees.id_user', '=', 'postings.id_user')
                ->where('replies.toAnswer_posting', '=', $data[$i]->id)
                ->select('replies.*', 'postings.*', 'employees.nickname')
                ->orderBy('postings.updated_at', 'desc')
                ->get();
            for ($j = 0; $j < count($appendData); $j++) {
                $dataUser = Posting::where('postings.id', '=', $appendData[$j]->toAnswer_posting)
                    ->join('employees', 'employees.id_user', '=', 'postings.id_user')
                    ->select('employees.nickname')->get();
                $appendData[$j]->toAnswer_posting = $dataUser[0]->nickname;
            }

            $appendData = $this->cekReplyData($appendData);
            array_push($lariknya, $appendData);
            $data[$i]->repliedData = $appendData;
        }
        return $data;
    }

    //page mengikuti
    public function indexFollowing()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $data = Posting::leftJoin('replies', 'replies.id_postings', '=', 'postings.id')
            ->leftJoin('employees', 'employees.id_user', '=', 'postings.id_user')
            // ->leftJoin('follow_users', 'follow_users.following_id', '=', 'postings.id_user')
            // ->where('follow_users.follow_status', '=', 1)
            ->where('postings.id_user', '!=', $user->id) //bukan yg login
            ->where('replies.toAnswer_posting', '=', null) //bukan sebuah komentar
            ->select('postings.*',  'employees.nickname', 'employees.fullname', 'employees.company')
            ->get();
        //apakah ngefollow?
        $cekFollow = FollowUser::where('id_user', '=', $user->id)->get();
        $combinedData = $data->map(function ($item) use ($cekFollow) {
            $follow = $cekFollow->where('following_id', $item->id_user)->first();
            if ($follow) {
                $item->follow_status = $follow->follow_status;
            } else {
                $item->follow_status = null;
            }
            return $item;
        });
        //apakah voting?
        $combinedData = $data->map(function ($item) use ($user) {
            $vote = Vote::where('id_user', '=', $user->id)
                ->where('id_postings', $item->id)
                ->first();
            $up = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '1')
                ->get();
            $down = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '2')
                ->get();
            $item->upvote = count($up);
            $item->downvote = count($down);
            $item->point = count($up) * 5 + count($down);

            if ($vote) {
                $item->vote_status = $vote->vote_status;
            } else {
                $item->vote_status = null;
            }
            return $item;
        });
        // Filter data with follow_status == 1
        $combinedData = $combinedData->filter(function ($item) {
            return $item->follow_status == 1;
        });

        // $data = $this->cekReplyData($data);
        return $this->showAll($combinedData);
    }

    public function profile($id)
    {
        // $user=Employee::where('id_user', $id);
        $user = DB::table('employees')
            ->where('employees.id_user', $id)
            ->get();
        return $user;
    }


    public function indexProfile($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        // $posting = Posting::where('id_user', '=', $user->id)->get();
        // // Retrieve all data from Employee model based on id_user
        // $userIds = $posting->pluck('id_user');
        // $employeeData = Employee::whereIn('id_user', $userIds)->get();

        // // Combine employee data with filtered data based on id_user
        // $combinedData = $posting->map(function ($item) use ($employeeData) {
        //     $user = $employeeData->where('id_user', $item->id_user)->first();
        //     if ($user) {
        //         $item->user = $user;
        //     }
        //     return $item;
        // });
        // // Gunakan values() untuk menghilangkan kunci indeks
        // $combinedData = $combinedData->values();
        // // $combinedData = $this->cekReplyData($combinedData);
        // return $this->showData($combinedData, 200);
        // // return response()->json(["data"=>$posting]);

        // Mengambil data posting yang bukan jawaban
        $postsNotAnswers = Posting::leftJoin('replies', 'postings.id', '=', 'replies.id_postings')
            ->leftJoin('employees', 'employees.id_user', '=', 'postings.id_user')
            // ->where('postings.id_user', '=', $user->id)
            ->where('postings.id_user', '=', $id)
            ->whereNull('replies.id')
            ->select('postings.*',  'employees.nickname', 'employees.fullname', 'employees.company')
            ->get();

        //apakah voting?
        $combinedData = $postsNotAnswers->map(function ($item) use ($user) {
            $vote = Vote::where('id_user', '=', $user->id)
                ->where('id_postings', $item->id)
                ->first();
            $up = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '1')
                ->get();
            $down = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '2')
                ->get();
            $item->upvote = count($up);
            $item->downvote = count($down);
            if ($vote) {
                $item->vote_status = $vote->vote_status;
            } else {
                $item->vote_status = null;
            }
            return $item;
        });

        return $this->showAll($combinedData);
    }
    public function indexProfileAnswer()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        // $posting = Posting::where('id_user', '=', $user->id)->get();
        // // Retrieve all data from Employee model based on id_user
        // $userIds = $posting->pluck('id_user');
        // $employeeData = Employee::whereIn('id_user', $userIds)->get();

        // // Combine employee data with filtered data based on id_user
        // $combinedData = $posting->map(function ($item) use ($employeeData) {
        //     $user = $employeeData->where('id_user', $item->id_user)->first();
        //     if ($user) {
        //         $item->user = $user;
        //     }
        //     return $item;
        // });
        // // Gunakan values() untuk menghilangkan kunci indeks
        // $combinedData = $combinedData->values();
        // // $combinedData = $this->cekReplyData($combinedData);
        // return $this->showData($combinedData, 200);
        // // return response()->json(["data"=>$posting]);

        // Mengambil data posting jawaban
        $postsNotAnswers = Posting::leftJoin('replies', 'postings.id', '=', 'replies.id_postings')
            ->leftJoin('employees', 'employees.id_user', '=', 'postings.id_user')
            ->where('postings.id_user', '=', $user->id)
            ->whereNotNull('replies.id')
            ->select('postings.*',  'employees.nickname', 'employees.fullname', 'employees.company')
            ->get();
        //apakah voting?
        $combinedData = $postsNotAnswers->map(function ($item) use ($user) {
            $vote = Vote::where('id_user', '=', $user->id)
                ->where('id_postings', $item->id)
                ->first();
            $up = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '1')
                ->get();
            $down = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '2')
                ->get();
            $item->upvote = count($up);
            $item->downvote = count($down);
            if ($vote) {
                $item->vote_status = $vote->vote_status;
            } else {
                $item->vote_status = null;
            }
            return $item;
        });

        return $this->showAll($combinedData);
    }
    public function showImagePost($image)
    {
        return response()->file(storage_path("app/public/posts/{$image}"));
    }

    public function downloadDoc($id)
    {
        $file = Posting::find($id);
        if ($file) {
            return response()->download(storage_path("app/public/posts/{$file->doc}"));
        }
        return response()->json(['msg' => 'File not found'], 404);
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
            'title',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
            'doc' => 'mimes:pdf,doc',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // $credential = Credential::where('id_employee', '=', $user->employees->id)
            //     ->where('type', '=', 0)->first();
            // if ($duplikasi->count() == 0) {
            $posting = new Posting();
            $posting->id_user = $user->employees->id;
            // $posting->id_credential = $credential->id;
            // $tag = json_decode($request->id_knowField) ?? 0;
            //cek ada tag or  tidak
            // if (count($tag) == 0) {
            //     $posting->id_knowField = null;
            // } else {
            $posting->id_knowField = $request->id_knowField ?? null;
            // }
            // $posting->id_credential = $user->employees->id;
            $posting->title = $request->title;
            $posting->description = $request->description;

            //gambar
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('posts', $imageName, 'public');

                $imagePath = 'storage/posts/' . $imageName;

                // Simpan informasi gambar dalam database
                $posting->image = $imageName;
            }
            if ($request->hasFile('doc')) {
                $doc = $request->file('doc');
                $docName = time() . '.' . $doc->getClientOriginalExtension();
                $doc->storeAs('posts', $docName, 'public');

                $docPath = 'storage/posts/' . $docName;

                // Simpan informasi gambar dalam database
                $posting->doc = $docName;
            }


            $posting->save();
            return response()->json($posting);
        }
    }

    //user lain
    public function indexProfiles($id)
    {
        // $user = JWTAuth::parseToken()->authenticate();
        // $user->employees; // memanggil fungsi relasi
        // $posting = Posting::where('id_user', '=', $user->id)->get();
        // // Retrieve all data from Employee model based on id_user
        // $userIds = $posting->pluck('id_user');
        // $employeeData = Employee::whereIn('id_user', $userIds)->get();

        // // Combine employee data with filtered data based on id_user
        // $combinedData = $posting->map(function ($item) use ($employeeData) {
        //     $user = $employeeData->where('id_user', $item->id_user)->first();
        //     if ($user) {
        //         $item->user = $user;
        //     }
        //     return $item;
        // });
        // // Gunakan values() untuk menghilangkan kunci indeks
        // $combinedData = $combinedData->values();
        // // $combinedData = $this->cekReplyData($combinedData);
        // return $this->showData($combinedData, 200);
        // // return response()->json(["data"=>$posting]);

        // Mengambil data posting yang bukan jawaban
        $postsNotAnswers = Posting::leftJoin('replies', 'postings.id', '=', 'replies.id_postings')
            ->leftJoin('employees', 'employees.id_user', '=', 'postings.id_user')
            ->where('postings.id_user', '=', $id)
            ->whereNull('replies.id')
            ->select('postings.*',  'employees.nickname', 'employees.fullname', 'employees.company')
            ->get();

        //apakah voting?
        $combinedData = $postsNotAnswers->map(function ($item) use ($id) {
            $vote = Vote::where('id_user', '=', $id)
                ->where('id_postings', $item->id)
                ->first();
            $up = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '1')
                ->get();
            $down = Vote::where('id_postings', '=', $item->id)
                ->where('vote_status', '2')
                ->get();
            $item->upvote = count($up);
            $item->downvote = count($down);
            if ($vote) {
                $item->vote_status = $vote->vote_status;
            } else {
                $item->vote_status = null;
            }
            return $item;
        });

        return $this->showAll($combinedData);
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
