<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\FollowCategory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class FollowCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param GET query
     * @param GET sort
     * @param GET direction
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        $data = new FollowCategory();
        $data = $data->join('users', 'follow_categories.id_user', '=', 'users.id');
        if (request()->has('query')) {
            $q = request()->get('query');
            $data = $data->where('users.username', 'LIKE', '%' . $q . '%')->orWhere('users.email', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowCategory::isFOLLOWED)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new FollowCategory();
        $data = $data->where('id_user', '=', $user->id);
        if (request()->has('user')) {
            $q = request()->get('user');
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', FollowCategory::isFOLLOWED)->get();

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

    /**
     * Regular User following a new category to go
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userFollowCategory($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        // Validation Requests
        $duplikasi = Category::where('id', '=', $id)->get();

        if ($duplikasi->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            $data = Category::find($duplikasi[0]->id);

            $followCategory = new FollowCategory();
            $followCategory->id_user = $user->id;
            $followCategory->follow_cat_id = $id;
            $followCategory->save();
            return $this->showMessage('You have just following \''.$data->name.'\' category');
        }
    }

    /**
     * Regular User unfollowing a new category to go
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userUnFollowCategory($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        // Validation Requests
        $duplikasi = Category::where('id', '=', $id)->get();

        if ($duplikasi->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            $data = Category::find($duplikasi[0]->id);

            $followCategory = new FollowCategory();
            $followCategory->id_user = $user->id;
            $followCategory->follow_cat_id = $id;
            $followCategory->follow_status = FollowCategory::isUNFOLLOWED;
            $followCategory->save();
            return $this->showMessage('You have just following \''.$data->name.'\' category');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FollowCategory  $followCategory
     * @return \Illuminate\Http\Response
     */
    public function show(FollowCategory $followCategory)
    {
        // pakai yang ada dari category/show/{id}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FollowCategory  $followCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(FollowCategory $followCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FollowCategory  $followCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'follow_cat_id' => 'required|numeric',
            'follow_status' => 'required|numeric|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $duplikasi = FollowCategory::where('id_user', '=', $request->id_user)->where('follow_cat_id', '=', $request->follow_cat_id)->where('follow_status', '=', $request->follow_status)->get();

            if ($duplikasi->count() == 0) {
                $followCategory = FollowCategory::find($id);
                $followCategory->id_user = $request->id_user;
                $followCategory->follow_cat_id = $request->follow_cat_id;
                $followCategory->follow_status = $request->follow_status;
                $followCategory->save();
                return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     * QUESTIONABLE
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FollowCategory  $followCategory
     * @return \Illuminate\Http\Response
     */
    public function updateSuperAdmin(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'follow_cat_id' => 'required|numeric',
            'follow_status' => 'required|numeric|in:0,1,2,3,4',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $duplikasi = FollowCategory::where('id_user', '=', $request->id_user)->where('follow_cat_id', '=', $request->follow_cat_id)->where('follow_status', '=', $request->follow_status)->get();

            if ($duplikasi->count() == 0) {
                $followCategory = FollowCategory::find($id);
                $followCategory->id_user = $request->id_user;
                $followCategory->follow_cat_id = $request->follow_cat_id;
                $followCategory->follow_status = $request->follow_status;
                $followCategory->save();
                return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage by User::SUPER_ADMIN
     *
     * @param  \App\Models\FollowCategory  $followCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        if($user->level != User::SUPER_ADMIN){
            $error['user'] = ['Unauthorized! You have no access to this API'];
            return $this->errorResponse($error, 401);
        }

        $data = new FollowCategory();
        $data = $data->where('id', '=', $id);
        if ($data->get()->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        }
        try {
            $data->first()->delete();
            return $this->showMessage(env('MIX_DELETE_DATA_SUCCESS'));
        } catch (Exception $e) {
            return $this->errorResponse(env('MIX_TIMEOUT_SESSION'), 419);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FollowCategory  $followCategory
     * @return \Illuminate\Http\Response
     */
    public function hiddenFollowing(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        if($user->level != User::ADMINS){
            $error['user'] = ['Unauthorized! You have no access to this API'];
            return $this->errorResponse($error, 401);
        }

        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'follow_cat_id' => 'required|numeric',
            'follow_status' => 'required|numeric|in:0,1,2',
        ]);

        // Validation Requests
        $duplikasi = Category::where('id', '=', $id)->get();

        if ($duplikasi->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            $followCategory = new FollowCategory();
            $followCategory->id_user = $user->id;
            $followCategory->follow_cat_id = $id;
            $followCategory->save();
        }
    }
}
