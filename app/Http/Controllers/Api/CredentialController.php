<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Credential;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CredentialController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        $data = new Credential();
        if (request()->has('query')) {
            $q = request()->get('query');
            $data = $data->where('description', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('type')) {
            $q = request()->get('type');
            $data = $data->where('type', '=', $q);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new Credential();
        $data = $data->where('id_employee','=',$user->employees->id);
        if (request()->has('query')) {
            $q = request()->get('query');
            $data = $data->where('description', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('type')) {
            $q = request()->get('type');
            $data = $data->where('type', '=', $q);
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
    public function create(Request $request)
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
            'type' => 'required|numeric|min:1|in:0,1,2,3,4,5',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = Credential::where('id_employee', '=', $user->employees->id)->where('type','=',$request->type)->where('description','=',$request->description)->get();

            if ($duplikasi->count() == 0) {
                $credential = new Credential();
                $credential->id_employee = $user->employees->id;
                $credential->type = $request->type;
                $credential->description = $request->description;
                $credential->save();
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param integer id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Credential::where('id', '=', $id)->get();

        if ($data->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            return $this->showData($data, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function edit(Credential $credential)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param integer id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|numeric|min:1|in:0,1,2,3,4,5',
            'description' => 'required',
            'hide' => 'required|numeric|in:0,1'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $credential = Credential::find($id);
            $duplikasi = Credential::where('id_employee', '=', $credential->id_employee)->where('type','=',$request->type)->where('description','=',$request->description)->get();

            if ($duplikasi->count() == 0) {
                $credential->type = $request->type;
                $credential->description = $request->description;
                $credential->hide = $request->hide;
                $credential->save();
                return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = new Credential();
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
