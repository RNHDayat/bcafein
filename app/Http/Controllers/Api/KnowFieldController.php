<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\knowField;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class KnowFieldController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new knowField();
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
        $data = $data->where('validation', '=', knowField::isVALIDATED)->get();

        return $this->showAll($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        $data = new knowField();
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
            'codeIlmu' => 'required|min:3|max:255|unique:know_fields,codeIlmu',
            'name' => 'required|min:2|max:255|string',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = knowField::where('codeIlmu', '=', $request->codeIlmu)->where('name', '=', $request->name)->get();

            if ($duplikasi->count() == 0) {
                $knowledge_field = new knowField();
                $knowledge_field->codeIlmu = $request->codeIlmu;
                $knowledge_field->name = $request->name;
                $knowledge_field->id_user_propose = $user->id;
                $knowledge_field->save();
                return $knowledge_field;

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
            'codeIlmu' => 'required|min:3|max:255|unique:know_fields,codeIlmu',
            'name' => 'required|min:2|max:255|string',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = knowField::where('codeIlmu', '=', $request->codeIlmu)->where('name', '=', $request->name)->get();

            if ($duplikasi->count() == 0) {
                $knowledge_field = new knowField();
                $knowledge_field->codeIlmu = $request->codeIlmu;
                $knowledge_field->name = $request->name;
                $knowledge_field->validation = knowField::isVALIDATED;
                $knowledge_field->id_user_validator = $user->id;
                $knowledge_field->save();

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
     * @param  integer id knowField
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = knowField::where('id', '=', $id)->get();

        if ($data->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            return $this->showData($data, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\knowField  $knowledge_field
     * @return \Illuminate\Http\Response
     */
    public function edit(knowField $knowledge_field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer id knowField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $validator = Validator::make($request->all(), [
            'codeIlmu' => 'required|min:3|max:255|unique:categories,codeCategory',
            'name' => 'required|min:2|max:255|string',
            'validation' => 'required|numeric|in:0,1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $duplikasi = knowField::where('codeIlmu', '=', $request->codeIlmu)->where('name', '=', $request->name)->get();

            if ($duplikasi->count() == 0) {
                $knowledge_field = knowField::find($id);
                $knowledge_field->codeIlmu = $request->codeIlmu;
                $knowledge_field->name = $request->name;
                $knowledge_field->validation = $request->validation;
                $knowledge_field->id_user_validator = $user->id;
                $knowledge_field->save();
                return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  integer id knowField
     * @return \Illuminate\Http\Response
     */
    public function validateKnowledgeField($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = knowField::where('id', '=', $id)->get();

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
     * Remove the specified resource from storage.
     *
     * @param  integer id knowField
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = new knowField();
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
