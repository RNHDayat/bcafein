<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Province;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new Province();
        if (request()->has('query')) {
            $q = request()->get('query');
            $data = $data->where('nama', 'LIKE', '%' . $q . '%')->orWhere('id', 'LIKE', '%' . $q . '%')->orWhere('kode_area', 'LIKE', '%' . $q . '%');
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
     * *Show the form for creating a new resource.
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:1|max:255|unique:provinces,nama',
            'kode_area' => 'required|min:1|max:255|unique:provinces,kode_area',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $province = new Province();
            $province->nama = $request->nama;
            $province->kode_area = $request->kode_area;
            $province->save();
            return $this->showMessage(env('MIX_ADD_DATA_SUCCESS'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  integer id province
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Province::where('id','=', $id)->get();

        if($data->count() == 0){
            return $this->errorResponse('Data does not exists', 404);
        } else{
            return $this->showData($data, 200);
        }
    }

    /**
     * *Show the form for editing the specified resource.
     *
     * @param  \App\Models\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function edit(Province $province)
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
            'nama' => 'required|min:1|max:255',
            'kode_area' => 'required|min:1|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $province = Province::find($id);
            $province->nama = $request->nama;
            $province->kode_area = $request->kode_area;
            $province->save();
            return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
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
        $data = new Province();
        $data = $data->where('id','=',$id);
        if($data->get()->count() == 0){
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
