<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Notifications;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $notification = Notifications::where('id_user',$user->id)->get();
        return response()->json($notification);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Mendapatkan notifikasi berdasarkan ID
        $notification = Notifications::find($id);
        // Memeriksa apakah notifikasi ditemukan dan status_read adalah isWAITING
        if ($notification && $notification->status_read == Notifications::isWAITING) {
            // Jika kondisi terpenuhi, maka lakukan pembaruan status menjadi isRead
            $notification->update(['status_read' => Notifications::isREAD]);
            // Mengembalikan respons JSON jika pembaruan berhasil
            return response()->json(['message' => 'Status updated successfully']);
        } else {
            // Jika kondisi tidak terpenuhi, mengembalikan respons JSON bahwa tidak ada pembaruan yang dibutuhkan
            return response()->json(['message' => 'No update needed']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
