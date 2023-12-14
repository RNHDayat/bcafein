<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Employee;
use App\Models\FollowUser;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

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
            return $follow_user;
        }
    }

    public function followers()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $user_followers = FollowUser::join('employees', 'employees.id_user', '=', 'follow_users.following_id')
            ->where('follow_users.id_user', '=', $user->id)
            ->where('follow_users.follow_status', '=', 1)
            ->get();
        return $user_followers;
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
        $data = FollowUser::where('id_user', '=', $following)->where('following_id', '=', $followed)->get();
        if ($data->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            $followData = $data[0];

            $followed_user = User::find($followData->following_id);
            if ($followed_user == null) {
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
    public function follow(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $validator = Validator::make($request->all(), [
            'following_id' => 'required',
            'follow_status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Kolom-kolom yang digunakan untuk pengecekan (misalnya, user_id dan status)
            $identifier = [
                'id_user' => $user->id,
                'following_id' => $request->following_id,
            ];
            // Data yang ingin diupdate atau dibuat
            $data = [
                'follow_status' => $request->follow_status,
            ];
            $userFollowing = Employee::find($request->following_id);
            $userFollow = Employee::find($user->id);
            $userToken = User::find($request->following_id);
            $jsonPayload = '{
            "registration_ids": ["' . $userToken->firebase_token . '"],
            "notification": {
                "title": "' . $userFollowing->nickname . '",
                "body": "' . $userFollow->nickname . ' mulai mengikuti anda"
                
            }
        }';
            // Mendekode JSON menjadi array PHP
            $payload = json_decode($jsonPayload, true);

            // Mengambil nilai dari properti "body"
            $title = $payload['notification']['title'];
            $body = $payload['notification']['body'];
            if ($request->follow_status == "1") {

                // Notifications::create(['id_user' => $request->following_id, 'title' => $title, 'body' => $body,]);
                // Pengecekan apakah data sudah ada berdasarkan id_user dan title
                $notification = Notifications::firstOrNew([
                    'id_user' => $request->following_id,
                    'body' => $body,
                ]);

                // Set nilai body jika data baru dibuat
                if (!$notification->exists) {
                    $notification->id_user_follow = $user->id;
                    $notification->title = $title;
                    $notification->save();
                    // return response()->json(['message' => 'Notification added successfully']);
                }

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $jsonPayload,
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: key=AAAADUkxPv0:APA91bENKeCCCkZ6iCC0K3s_U-CsZY5lU7cdzIEvbXkone4ctccXaJ3hMAsH8zLqnzNFjyaH2Dgx7gCbSRUk2XXL5cybv1kETfLN5SeuyMRMuU9OeTVjE07QX9ycNdzK_BxibbIvjJcn',
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                echo $response;
            } else if ($request->follow_status == "3") {
                Notifications::where('id_user', '=', $request->following_id)->where('body', '=', $body)->delete();
            }


            // Update atau create data
            $follow = FollowUser::updateOrCreate($identifier, $data);
            // $follow->save();
            // $user->save();
            return $this->showData($follow, 200);
        }
    }
    public function showfollowings()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $user_followers = FollowUser::join('employees', 'employees.id_user', '=', 'follow_users.id_user')
            ->where('follow_users.following_id', '=', $user->id)
            ->where('follow_status', '=', 1)
            ->get();
        return $user_followers;
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
