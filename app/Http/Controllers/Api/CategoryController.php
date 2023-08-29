<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = JWTAuth::parseToken()->authenticate();
        // $user->employees; // memanggil fungsi relasi

        $data = new Category();
        if (request()->has('query')) {
            $q = request()->get('query');
            $data = $data->where('name', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('proposer')) {
            $q = request()->get('proposer');
            $data = $data->where('id_user_propose', '=', $q);
        }
        if (request()->has('validator')) {
            $q = request()->get('validator');
            $data = $data->where('id_user_validator', '=', $q);
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->where('validation', '=', Category::isVALIDATED)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        // $user = JWTAuth::parseToken()->authenticate();
        // $user->employees; // memanggil fungsi relasi

        $data = new Category();
        if (request()->has('query')) {
            $q = request()->get('query');
            $data = $data->where('name', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('proposer')) {
            $q = request()->get('proposer');
            $data = $data->where('id_user_propose', '=', $q);
        }
        if (request()->has('validator')) {
            $q = request()->get('validator');
            $data = $data->where('id_user_validator', '=', $q);
        }
        if (request()->has('sort')) {
            $data = $data->orderBy(request()->get('sort'), request()->get('direction'));
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->get();

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
            'codeCategory' => 'required|min:3|max:255|unique:categories,codeCategory',
            'name' => 'required|min:2|max:255|string',
            'id_Ilmu' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = Category::where('codeCategory', '=', $request->codeCategory)->where('name', '=', $request->name)->where('primary_major', '=', $request->id_Ilmu)->get();

            if ($duplikasi->count() == 0) {
                $categories = new Category();
                $categories->codeCategory = $request->codeCategory;
                $categories->name = $request->name;
                $categories->id_Ilmu = $request->id_Ilmu;
                $categories->id_user_propose = $user->id;
                $categories->save();

                // Merit System
                # Code here...
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmin(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        // Validation Requests
        $validator = Validator::make($request->all(), [
            'codeCategory' => 'required|min:3|max:255|unique:categories,codeCategory',
            'name' => 'required|min:2|max:255|string',
            'id_Ilmu' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = Category::where('codeCategory', '=', $request->codeCategory)->where('name', '=', $request->name)->where('primary_major', '=', $request->id_Ilmu)->get();

            if ($duplikasi->count() == 0) {
                $categories = new Category();
                $categories->codeCategory = $request->codeCategory;
                $categories->name = $request->name;
                $categories->id_Ilmu = $request->id_Ilmu;
                $categories->id_user_propose = $user->id;
                $categories->validation = Category::isVALIDATED;
                $categories->id_user_validator = $user->id;
                $categories->save();

                // Merit System
                # Code here...
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  integer id Categories
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Category::where('id', '=', $id)->get();

        if ($data->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            return $this->showData($data, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer id Categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $validator = Validator::make($request->all(), [
            'codeCategory' => 'required|min:3|max:255|unique:categories,codeCategory',
            'name' => 'required|min:2|max:255|string',
            'id_Ilmu' => 'required|numeric',
            'validation' => 'required|numeric|in:0,1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $duplikasi = Category::where('codeCategory', '=', $request->codeCategory)->where('name', '=', $request->name)->where('primary_major', '=', $request->id_Ilmu)->get();

            if ($duplikasi->count() == 0) {
                $categories = Category::find($id);
                $categories->codeCategory = $request->codeCategory;
                $categories->name = $request->name;
                $categories->validation = $request->validation;
                $categories->id_Ilmu = $request->id_Ilmu;
                $categories->id_user_validator = $user->id;
                $categories->save();
                return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  integer id Categories
     * @return \Illuminate\Http\Response
     */
    public function validateCategory($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = Category::where('id', '=', $id)->get();

        if ($data == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            $categories = $data[0];
            $categories->validation = 1;
            $categories->id_user_validator = $user->id;
            $categories->save();

            return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
        }
    }

    /**
     * Remove the specified resource from storage by User::SUPER_ADMIN
     *
     * @param  integer id Categories
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = new Category();
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
}
