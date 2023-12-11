<?php

namespace App\Http\Controllers\Api;

use App\Models\Media;
use App\Models\Aturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\JenisAturan;
use App\Models\KategoriAturan;

class CafeinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRegulasi()
    {
        
        $data = Aturan::join('jenis', 'jenis.id', '=', 'aturan.id_jenis')
            ->select('aturan.*','jenis.name')
            ->get();

        // foreach ($data as $item) {
        //     $item->id_kategori = json_encode($item->id_kategori);
        // }

        return response()->json(["data" => $data]);
    }

    public function downloadRegulasi($id)
    {
        $file = Aturan::find($id);
        if ($file) {
            return response()->download(storage_path("app/public/produk/{$file->doc}"));
        }
        return response()->json(['message' => 'File not found'], 404);
    }
    public function viewRegulasi($id)
    {
        $file = Aturan::find($id);
        if ($file) {
            return response()->file(storage_path("app/public/produk/{$file->doc}"));
        }
        return response()->json(['message' => 'File not found'], 404);
    }

    public function viewFileMedia($id)
    {
        $file = Media::find($id);
        if ($file) {
            return response()->file(storage_path("app/public/attachment/{$file->attachment}"));
        }
        return response()->json(['message' => 'File not found'], 404);
    }

    public function indexMedia()
    {
        // $user = JWTAuth::parseToken()->authenticate();
        // $data = DB::table('aturan')->get();
        $data = Media::all();

        // foreach ($data as $item) {
        //     // $item->id_kategori = json_decode($item->id_kategori);
        //     // Ubah properti "name" menjadi "nama_jenis
        //     $item["cover"] = asset("storage/app/public/media/{$item['cover']}");
        //     // $item["nama_jenis"] = $item["name"];
        //     // unset($item["name"]);
        // }
        // Mengembalikan URL gambar.
    return response()->json([
        "data" => $data,
        // 'gambar_url' => asset("storage/app/public/media/$data->cover")

    ]);
        // return response()->json([]);
    }

    public function downloadFileMedia($id)
    {
        $file = Media::find($id);
        if ($file) {
            return response()->download(storage_path("app/public/attachment/{$file->attachment}"));
        }
        return response()->json(['msg' => 'File not found'], 404);
    }
    public function showCover($coverName){
        // $image = Media::where('cover','=',$coverName)->get();
        
        return response()->file(storage_path("app/public/media/{$coverName}"));
    }

    public function indexJenis(){
        $data = JenisAturan::all();
        return response()->json($data);
    }
    public function indexKategori(){
        $data = KategoriAturan::all();
        return response()->json($data);
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
        //
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
