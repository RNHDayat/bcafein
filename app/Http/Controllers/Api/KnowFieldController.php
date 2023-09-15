<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Posting;
use App\Models\knowField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Employee;
use App\Models\Reply;
use Illuminate\Support\Facades\Validator;

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

    public function follow(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        //get data knowField
        $record = knowField::where('codeIlmu', '=', $request->codeIlmu)->first();
        // $record = knowField::find('codeIlmu', '=', $request->codeIlmu);
        if ($record->id_user_follow == null && $record->user_status_follow == null) {
            // Jika id_user tidak ada dalam array, tambahkan id_user baru
            $id_user_list[] = $user->id;
            // Juga tambahkan status_follow baru == following(1)
            $status_follow_list[] = 1;
            // Konversi array PHP kembali menjadi string JSON
            $record->id_user_follow = json_encode($id_user_list);
            $record->user_status_follow = json_encode($status_follow_list);

            // Simpan perubahan ke database
            $record->save();
        } else {
            // Konversi string JSON menjadi array PHP
            $id_user_list = json_decode($record->id_user_follow);
            $status_follow_list = json_decode($record->user_status_follow);
            // Cari indeks di mana id_user adalah id_user_login
            $index = array_search($user->id, $id_user_list);

            // Jika ditemukan, update status_follow menjadi 3
            if ($index !== false) {
                if ($status_follow_list[$index] == 1) {
                    $status_follow_list[$index] = 2; //unfollow
                } else {
                    $status_follow_list[$index] = 1; //follow
                }
            } else {
                // Jika id_user tidak ada dalam array, tambahkan id_user baru
                $id_user_list[] = $user->id;
                // Juga tambahkan status_follow baru == following(1)
                $status_follow_list[] = 1;
            }

            // Konversi array PHP kembali menjadi string JSON
            $record->id_user_follow = json_encode($id_user_list);
            $record->user_status_follow = json_encode($status_follow_list);

            // Simpan perubahan ke database
            $record->save();
        }
        return $this->showData($record, 200);

        // return response()->json(['data' => $record]);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer id knowField
     * @return \Illuminate\Http\Response
     */
    public function showFollowIlmu()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        // Ambil data yang memiliki id_user = 5 dan status_follow = 1
        $results = knowField::where('id_user_follow', '!=', null)
            ->where('user_status_follow', '!=', null)
            ->get();
        // Transform "id_knowField" into arrays if not null
        foreach ($results as $item) {
            $item->id_user_follow = json_decode($item->id_user_follow);
            $item->user_status_follow = json_decode($item->user_status_follow);
            $index = array_search($user->id, $item->id_user_follow);
            if ($index !== false) {
                $item->id_user_follow = $item->id_user_follow[$index];
                $item->user_status_follow = $item->user_status_follow[$index];
            }
        }
        // Filter hanya data dengan user_status_follow = 1
        $filteredResults = $results->filter(function ($item) {
            return $item->user_status_follow == 1;
        });
        // Gunakan values() untuk menghilangkan kunci indeks
        $filteredResults = $filteredResults->values();

        return response()->json($filteredResults);
        // $results = $results->pluck('user_status_follow');


        // Filter data based on "id_knowField" that matches $codeIlmu
        // $filteredData = $results->filter(function ($item) use ($user) {
        //     return in_array($user->id, $item->id_user_follow) && in_array(1, $item->user_status_follow);
        // });
        // foreach ($results as $items) {
        //     $items->id_user_follow = json_decode($items->id_user_follow);
        //     $items->user_status_follow = json_decode($items->user_status_follow);
        //     $index = array_search($user->id, $items->id_user_follow);
        //     if ($index !== false) {
        //         $items->id_user_follow = $items->id_user_follow[$index];
        //         $items->user_status_follow = $items->user_status_follow[$index];
        //     }
        // }
    }
    public function show($codeIlmu)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->employees; // memanggil fungsi relasi
        $data = knowField::where('codeIlmu', '=', $codeIlmu)->first();
        if ($data->id_user_follow != null && $data->user_status_follow != null) {
            $id_user_list = json_decode($data->id_user_follow);
            $status_follow_list = json_decode($data->user_status_follow);
            // Cari indeks di mana id_user adalah id_user_login
            $index = array_search($user->id, $id_user_list);

            // Jika ditemukan, update status_follow 
            if ($index !== false) {
                $id_user = $id_user_list[$index];
                $status_follow = $status_follow_list[$index];
            }
            return response()->json(['id' => $id_user, 'status_follow' => $status_follow]);
        }
        return response()->json(['id' => $data->id, 'status_follow' => 2]);


        // if ($data == null) {
        // } else {
        //     // return $this->showData($data, 200);
        // }
    }

    //show posting ruang
    public function showDetail($codeIlmu)
    {
        $data = Posting::where('id_knowField', '!=', null)->get();

        // Transform "id_knowField" into arrays if not null
        foreach ($data as $item) {
            if ($item->id_knowField !== null) {
                $item->id_knowField = json_decode($item->id_knowField);
            }
        }

        // Filter data based on "id_knowField" that matches $codeIlmu
        $filteredData = $data->filter(function ($item) use ($codeIlmu) {
            return in_array($codeIlmu, $item->id_knowField);
        });

        if ($filteredData->isEmpty()) {
            return $this->errorResponse('Data does not exist', 404);
        } else {
            // Retrieve all data from Employee model based on id_user
            $userIds = $filteredData->pluck('id_user');
            $employeeData = Employee::whereIn('id_user', $userIds)->get();

            // Combine employee data with filtered data based on id_user
            $combinedData = $filteredData->map(function ($item) use ($employeeData) {
                $user = $employeeData->where('id_user', $item->id_user)->first();
                if ($user) {
                    $item->user = $user;
                }
                return $item;
            });
            // Gunakan values() untuk menghilangkan kunci indeks
            $combinedData = $combinedData->values();
            // $combinedData = $this->cekReplyData($combinedData);

            return $this->showData($combinedData, 200);
        }
    }


    private function cekReplyData($data)
    {
        $lariknya = array();
        for ($i = 0; $i < count($data); $i++) {
            $appendData = Reply::join('postings', 'replies.id_postings', '=', 'postings.id')->where('replies.toAnswer_posting', '=', $data[$i]->id_postings)->orderBy('postings.updated_at', 'desc')->get();
            $appendData = $this->cekReplyData($appendData);
            array_push($lariknya, $appendData);
            $data[$i]->repliedData = $appendData;
        }
        return $data;
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
