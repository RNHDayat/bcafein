<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Education;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * *Daftar Strata dan nilainya
 *
 * 0=sarjana
 * 1=magister
 * 2=doktoral
 * 3=profesi
 * 4=lainnya (shi, sengaja saya paling bedakan)
 * 5=SMA/MA/MTS/SMK
 * 6=SMP/sederajat
 * 7=SD
 */

class EducationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new Education();
        if (request()->has('school')) {
            $q = request()->get('school');
            $data = $data->where('school', 'LIKE', '%' . $q . '%');
        }
        if (request()->has('major')) {
            $q = request()->get('major');
            $data = $data->where('primary_major', 'LIKE', '%' . $q . '%')->where('id_employee', '=', $user->employees->id)->orWhere('secondary_major', 'LIKE', '%' . $q . '%')->where('id_employee', '=', $user->employees->id);
        }
        if (request()->has('strata')) {
            $q = request()->get('strata');
            $data = $data->where('strata', '=', $q);
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
            'school' => 'required|min:3|max:255',
            'primary_major' => 'required|min:2|max:255',
            'secondary_major' => 'min:2|max:255',
            'strata' => 'required|numeric|in:0,1,2,3,4,5,6,7',
            'degree_type' => 'string|min:2|max:12',
            'graduation_year' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = Education::where('id_employee', '=', $user->employees->id)->where('school', '=', $request->school)->where('primary_major', '=', $request->primary_major)->where('secondary_major', '=', $request->secondary_major)->where('strata', '=', $request->strata)->where('graduation_year', '=', $request->graduation_year)->get();

            if ($duplikasi->count() == 0) {
                $edukasi = new Education();
                $edukasi->id_employee = $user->employees->id;
                $edukasi->school = $request->school;
                $edukasi->primary_major = $request->primary_major;
                $edukasi->secondary_major = $request->secondary_major;
                $edukasi->strata = $request->strata;
                $edukasi->degree_type = $request->degree_type;
                $edukasi->graduation_year = $request->graduation_year;
                $edukasi->save();

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
     * @param  integer id education
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Education::where('id', '=', $id)->get();

        if ($data->count() == 0) {
            return $this->errorResponse('Data does not exists', 404);
        } else {
            return $this->showData($data, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function edit(Education $education)
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
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $validator = Validator::make($request->all(), [
            'school' => 'required|min:3|max:255',
            'primary_major' => 'required|min:2|max:255',
            'secondary_major' => 'min:2|max:255',
            'strata' => 'required|numeric|in:0,1,2,3,4,5,6,7',
            'degree_type' => 'string|min:2|max:12',
            'graduation_year' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            $duplikasi = Education::where('id_employee', '=', $user->employees->id)->where('id', '!=', $id)->where('school', '=', $request->school)->where('primary_major', '=', $request->primary_major)->where('secondary_major', '=', $request->secondary_major)->where('strata', '=', $request->strata)->where('graduation_year', '=', $request->graduation_year)->get();

            if ($duplikasi->count() == 0) {
                $edukasi = Education::find($id);
                $edukasi->school = $request->school;
                $edukasi->primary_major = $request->primary_major;
                $edukasi->secondary_major = $request->secondary_major;
                $edukasi->strata = $request->strata;
                $edukasi->degree_type = $request->degree_type;
                $edukasi->graduation_year = $request->graduation_year;
                $edukasi->save();
                return $this->showMessage(env('MIX_UPDATE_DATA_SUCCESS'));
            } else {
                return $this->errorResponse("Duplicate data! The requested education data is already exists", 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage by User::REGULAR_USER
     *
     * @param integer id
     * @return \Illuminate\Http\Response
     */
    public function userDestroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        $data = new Education();
        $data = $data->where('id', '=', $id)->where('id_employee','=',$user->employees->id);
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
     * Remove the specified resource from storage by User::ADMINS
     *
     * @param integer id
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        if($user->level != User::ADMINS){
            $error['user'] = ['Unauthorized! You have no access to this API'];
            return $this->errorResponse($error, 401);
        }

        $data = new Education();
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
