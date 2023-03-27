<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\FollowUser;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class FollowUserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFollowTHEM()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new FollowUser();
        $data = $data->join('users', 'follow_users.following_id', '=', 'users.id')->where('follow_users.id_user', '=', $user->id);
        if (request()->has('user')) {
            $q = request()->get('user');
            $data = $data->where('users.username', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowUser::isFOLLOWED)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFollowME()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new FollowUser();
        $data = $data->join('users', 'follow_users.following_id', '=', 'users.id')->where('follow_users.following_id', '=', $user->id);
        if (request()->has('user')) {
            $q = request()->get('user');
            $data = $data->where('users.username', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowUser::isFOLLOWED)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWaitingTHEM()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new FollowUser();
        $data = $data->join('users', 'follow_users.following_id', '=', 'users.id')->where('follow_users.following_id', '=', $user->id);
        if (request()->has('user')) {
            $q = request()->get('user');
            $data = $data->where('users.username', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowUser::isWAITING)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWaitingME()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new FollowUser();
        $data = $data->join('users', 'follow_users.following_id', '=', 'users.id')->where('follow_users.following_id', '=', $user->id);
        if (request()->has('user')) {
            $q = request()->get('user');
            $data = $data->where('users.username', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowUser::isWAITING)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUnFolTHEM()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new FollowUser();
        $data = $data->join('users', 'follow_users.following_id', '=', 'users.id')->where('follow_users.following_id', '=', $user->id);
        if (request()->has('user')) {
            $q = request()->get('user');
            $data = $data->where('users.username', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowUser::isUNFOLLOWED)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUnFolME()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new FollowUser();
        $data = $data->join('users', 'follow_users.following_id', '=', 'users.id')->where('follow_users.following_id', '=', $user->id);
        if (request()->has('user')) {
            $q = request()->get('user');
            $data = $data->where('users.username', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowUser::isUNFOLLOWED)->get();

        return $this->showAll($data);
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

    /**BELUM SELESAI!!
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function following($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $users_followed = User::find($id);
        // $users_blocked = ;
        $duplikasi = FollowUser::where('id_user', '=', $user->id)->where('following_id', '=', $id)->get();
        if ($duplikasi->count() == 0) {
            $follow_user = new FollowUser();
            $follow_user->id_user = $user->id;
            $follow_user->following_id = $id;
            if ($users_followed->private_account == User::ACTIVE) {
                $follow_user->follow_status = FollowUser::isWAITING;
            } else {
                $follow_user->follow_status = FollowUser::isFOLLOWED;
            }
            $follow_user->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $followed sebagai yang diikuti, data 'following_id'
     * @param  integer $following sebagai yang mengikuti, data 'id_users'
     * @return \Illuminate\Http\Response
     */
    public function show($followed, $following)
    {
        $data = FollowUser::where('id_user','=',$following)->where('following_id','=',$followed)->get();
        if($data->count() == 0){
            return $this->errorResponse('Data does not exists', 404);
        } else {
            $followData = $data[0];

            $followed_user = User::find($followData->following_id);
            if($followed_user == null){
                return $this->errorResponse('Data does not exists', 404);
            }

            $following_user = User::find($followData->id_user);
            if ($following_user == null) {
                return $this->errorResponse('Data does not exists', 404);
            }

            $followData = ['user' => $following_user, 'following' => $followed_user];
            return $this->showData($followData, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FollowUser  $followUser
     * @return \Illuminate\Http\Response
     */
    public function edit(FollowUser $followUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FollowUser  $followUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FollowUser $followUser)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FollowUser  $followUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(FollowUser $followUser)
    {
        //
    }
}
