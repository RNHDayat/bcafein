<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Encryption;
use App\Http\Controllers\ApiController;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmployeeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employe = Employee::all();
        return $employe;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $level = 999;
        if (request()->has('level')) {
            $validator = Validator::make($request->all(), [
                'level' => 'string|min:3|max:3000|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }
            $encrypt = new Encryption();
            $level = $encrypt->decrypt($request->level, config('secretKey.secretKey'));
        }

        if ($level == 2 || $level == 1 || $level == 0) {
            $employee = Employee::where('id', '=', $id)->get()[0];
            $user = User::where('id', '=', $employee->id_user)->get()[0];
        } else {
            $employee = Employee::where('id', '=', $id)->get()->makeHidden(['datebirth', 'birthplace', 'npwp', 'lat_house', 'long_house', 'address_house', 'created_at', 'updated_at'])[0];
            $user = User::where('id', '=', $employee->id_user)->get()->makeHidden(['firebase_token', 'last_login_at', 'imageava_path', 'created_at', 'updated_at', 'status'])[0];
        }
        return $this->showData([
            'employee' => $employee,
            'user' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    public function updateFullname(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi

        // Validation Requests
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        } else {
            // Check if the requested data is duplicate
            $duplikasi = Employee::where('id_user', '=', $user->id)->where('fullname', '=', $request->fullname)->first();

            if (!$duplikasi) {
                Employee::where('id_user', '=', $user->id)->update(["fullname" => $request->fullname]);
                return response()->json(['msg' => 'Berhasil memperbarui nama'], 200);
                // $employee->id = $user->id;
                // $employee->fullname = $request->fullname;
                // $employee->save();
            } else {
                return response()->json(['msg' => 'Harap ubah terlebih dahulu'], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
