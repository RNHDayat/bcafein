<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\PhoneCountry;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhoneCountryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new PhoneCountry();
        if (request()->has('query')) {
            $q = request()->get('query');
            $data = $data->where('nicename', 'LIKE', '%' . $q . '%')->orWhere('phonecode', 'LIKE', '%' . $q . '%')->orWhere('numcode', 'LIKE', '%' . $q . '%');
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
        $validator = Validator::make($request->all(), [
            'iso' => 'required|string|min:1|max:2',
            'name' => 'required|string|min:3|max:255',
            'nicename' => 'required|string|min:3|max:255|unique:phone_countries,nicename',
            'iso3' => 'string|min:1|max:3',
            'numcode' => 'required|numeric',
            'phonecode' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = PhoneCountry::where('nicename', '=', $request->nicename)->orWhere('phonecode', '=', $request->phonecode)->get();

            if ($duplikasi->count() == 0) {
                $phoneCountry = new PhoneCountry();
                $phoneCountry->iso = $request->iso;
                $phoneCountry->name = $request->name;
                $phoneCountry->nicename = $request->nicename;
                $phoneCountry->iso3 = $request->iso3;
                $phoneCountry->numcode = $request->numcode;
                $phoneCountry->phonecode = $request->phonecode;
                $phoneCountry->save();

                return $this->showMessage(env('MIX_ADD_DATA_SUCCESS'));
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PhoneCountry::where('id', '=', $id)->get();

        if ($data->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            return $this->showData($data, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PhoneCountry  $phoneCountry
     * @return \Illuminate\Http\Response
     */
    public function edit(PhoneCountry $phoneCountry)
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
            'iso' => 'required|string|min:1|max:2',
            'name' => 'required|string|min:3|max:255',
            'nicename' => 'required|string|min:3|max:255|unique:phone_countries,nicename',
            'iso3' => 'string|min:1|max:3',
            'numcode' => 'required|numeric',
            'phonecode' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $phoneCountry = PhoneCountry::find($id);
            $phoneCountry->iso = $request->iso;
            $phoneCountry->name = $request->name;
            $phoneCountry->nicename = $request->nicename;
            $phoneCountry->iso3 = $request->iso3;
            $phoneCountry->numcode = $request->numcode;
            $phoneCountry->phonecode = $request->phonecode;
            $phoneCountry->save();
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
        $data = new PhoneCountry();
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
