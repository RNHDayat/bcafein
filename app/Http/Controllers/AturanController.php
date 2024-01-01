<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\JenisAturan;
use Illuminate\Http\Request;
use App\Models\KategoriAturan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AturanController extends Controller
{
    public function index()
    {
        $aturan = Aturan::paginate(8);
        $title = 'Hapus aturan!';
        $text = "Apakah anda yakin ingin menghapus ini?";
        confirmDelete($title, $text);
        return view('aturan.aturan', compact('aturan'));
    }

    function action(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            $perPage = 10; // Anda dapat mengatur jumlah data per halaman sesuai kebutuhan

            if ($query != '') {
                $data = Aturan::join('jenis', 'jenis.id', '=', 'id_jenis')
                    ->select('aturan.*', 'jenis.name')
                    ->where('nomor', 'like', '%' . $query . '%')
                    ->paginate($perPage);
            } else {
                $data = Aturan::join('jenis', 'jenis.id', '=', 'id_jenis')
                    ->select('aturan.*', 'jenis.name')
                    ->paginate($perPage);
            }

            $total_row = $data->total();
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    // id_kategori sudah merupakan array, berkat casting di model
                    $kategoriTexts = [];

                    foreach ($row->id_kategori as $kategori) {
                        if (isset($kategori['text'])) {
                            $kategoriTexts[] = $kategori['text'];
                        }
                    }

                    // Gabungkan teks dengan pemisah koma
                    $kategoriText = implode(', ', $kategoriTexts);
                    $output .= '
                <tr>
                <td>' . ($data->firstItem() + $index) . '</td>
                <td>' . $row->short_desc . '</td>
                <td>' . $row->name . '</td>
                <td>' . $kategoriText . '</td>
                <td>' . $row->nomor . '</td>
                <td>' . $row->tahun . '</td>
                <td>
                    <a href="' . route('download.aturan', $row->doc) . '" ></i> ' . $row->doc . '</a>
                </td>
                <td>' . $row->oleh . '</td>
                <td > 
                    <a href="' . route('show.aturan', $row->id) . '" class="btn btn-success btn-sm m-1"><i class="fas fa-edit" style="color: #ffffff;"></i> Edit</a>
                    <a href="' . route('delete.aturan', $row->id) . '" data-confirm-delete="true" class="btn btn-danger btn-sm"><i class="fas fa-trash" style="color: #ffffff;"></i> Hapus</a>
                </td>
                </tr>
                ';
                }
            } else {
                $output = '
            <tr>
                <td align="center" colspan="5">No Data Found</td>
            </tr>
            ';
            }

            // Menambahkan link pagination
            $pagination_link = $data->links();

            $data = array(
                'table_data'   => $output,
                'total_data'   => $total_row,
                'pagination'   => strval($pagination_link), // Konversi HTML pagination menjadi string
            );

            echo json_encode($data);
        }
    }

    public function download($doc)
    {
        return response()->download(storage_path('app/public/produk/' . $doc));
    }


    public function create()
    {
        $jenis = JenisAturan::get();
        $kategori =  KategoriAturan::get();
        return view('aturan.tambah', compact('jenis', 'kategori'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jenis' => 'required',
            'id_kategori' => 'required',
            'nomor' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'short_desc' => 'required|string|max:255',
            'keterangan' => 'required|string|max:1000',
            'doc' => 'required|file|mimes:pdf,doc,docx|max:2048', // Contoh untuk file dokumen
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->hasFile('doc')) {
            $doc = $request->file('doc');
            $docName = $doc->getClientOriginalName();
            // Cek duplikasi nama file
            if (Aturan::where('doc', $docName)->exists()) {
                toast('Dokumen  sudah ada', 'error')->autoClose(3000)->timerProgressBar();
                return redirect()->back()->withInput();
            }
        }

        $selectedItems = $request->id_kategori; // Ini akan menjadi array seperti ['14:Lingkungan', '11:Tata Ruang']
        // Mengubah data menjadi format array yang diinginkan
        $jsonData = array_map(function ($item) {
            $parts = explode(':', $item, 2); // Pisahkan id dan name

            // Periksa apakah kita memiliki kedua bagian (id dan name)
            if (count($parts) === 2) {
                [$id, $name] = $parts;
                return ['id' => $id, 'text' => $name];
            } else {
                // Handle kasus di mana format tidak sesuai
                // Misalnya, kembalikan null atau lakukan tindakan lain
                return null;
            }
        }, $selectedItems);
        // Filter out null values
        $jsonData = array_filter($jsonData);
        // Buat objek Aturan baru dan isi dengan data dari request
        $aturan = new Aturan();
        $aturan->id_jenis = $request->id_jenis;
        $aturan->id_kategori = $jsonData;
        $aturan->nomor = $request->nomor;
        $aturan->tahun = $request->tahun;
        $aturan->short_desc = $request->short_desc;
        $aturan->keterangan = $request->keterangan;
        $aturan->oleh = "Administrator";
        $doc->storeAs('produk', $docName, 'public');

        // Simpan informasi gambar dalam database
        $aturan->doc = $docName;
        $aturan->save();
        toast('Berhasil menambahkan aturan regulasi', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/aturan');
    }

    public function getKategori(Request $request)
    {
        $id_kategori = [];
        if ($search = $request->name) {
            $id_kategori = KategoriAturan::where('name', 'like', "%$search%")->get();
        }
        return response()->json($id_kategori);
    }

    public function show($id)
    {
        $aturan = Aturan::where('id', $id)->get();
        $jenis = JenisAturan::get();
        $kategori =  KategoriAturan::get();
        return view('aturan.edit', compact('aturan', 'jenis', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $selectedItems = $request->id_kategori; // Ini akan menjadi array seperti ['14:Lingkungan', '11:Tata Ruang']
        // Mengubah data menjadi format array yang diinginkan
        $jsonData = array_map(function ($item) {
            $parts = explode(':', $item, 2); // Pisahkan id dan name

            // Periksa apakah kita memiliki kedua bagian (id dan name)
            if (count($parts) === 2) {
                [$id, $name] = $parts;
                return ['id' => $id, 'text' => $name];
            } else {
                // Handle kasus di mana format tidak sesuai
                // Misalnya, kembalikan null atau lakukan tindakan lain
                return null;
            }
        }, $selectedItems);
        // Filter out null values
        $jsonData = array_filter($jsonData);
        // Buat objek Aturan baru dan isi dengan data dari request
        $aturan = Aturan::where('id', $id)->first();
        if ($request->hasFile('doc')) {
            // Hapus file lama
            Storage::disk('public')->delete('produk/' . $aturan->doc);
            $doc = $request->file('doc');
            //tambah file baru
            $docName = $doc->getClientOriginalName();
            $doc->storeAs('produk', $docName, 'public');
            // Perbarui model Aturan
            $aturan->update([
                'id_jenis' => $request->id_jenis,
                'id_kategori' => $jsonData,
                'nomor' => $request->nomor,
                'tahun' => $request->tahun,
                'short_desc' => $request->short_desc,
                'keterangan' => $request->keterangan,
                'oleh' => "Administrator",
                'doc' => $docName,
            ]);
            toast('Berhasil mengubah aturan', 'success')->autoClose(3000)->timerProgressBar();
        } else {
            $aturan->update([
                'id_jenis' => $request->id_jenis,
                'id_kategori' => $jsonData,
                'nomor' => $request->nomor,
                'tahun' => $request->tahun,
                'short_desc' => $request->short_desc,
                'keterangan' => $request->keterangan,
                'oleh' => "Administrator",
            ]);
            toast('Berhasil mengubah aturan', 'success')->autoClose(3000)->timerProgressBar();
        }
        return redirect('/aturan');
    }

    public function destroy($id)
    {
        $aturan = Aturan::where('id', $id)->first();
        Storage::disk('public')->delete('produk/' . $aturan->doc);
        $aturan->delete();

        toast('Berhasil menghapus aturan', 'success')->autoClose(3000)->timerProgressBar();
        return back();
    }
}
