<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::paginate(8);
        $title = 'Hapus media!';
        $text = "Apakah anda yakin ingin menghapus ini?";
        confirmDelete($title, $text);
        return view('media.media');
    }

    function action(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            $perPage = 10; // Anda dapat mengatur jumlah data per halaman sesuai kebutuhan

            if ($query != '') {
                $data = Media::where('headline', 'like', '%' . $query . '%')
                    ->paginate($perPage);
            } else {
                $data = Media::paginate($perPage);
            }
            $total_row = $data->total();
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $coverLink = $row->cover != null ? '<a href="' . route('downloadCover.media', $row->cover) . '">' . $row->cover . '</a>' : '-';
                    $attachmentLink = $row->attachment ? '<a href="' . route('downloadDoc.media', $row->attachment) . '">' . $row->attachment . '</a>' : '-';

                    $output .= '
                        <tr>
                        <td>' . ($data->firstItem() + $index) . '</td>
                        <td>' . $row->nama_media . '</td>
                        <td>' . $row->headline . '</td>
                        <td>' . $row->no_volume . '</td>
                        <td>' . $row->tahun . '</td>
                        <td>' . $coverLink . '</td>
                        <td>' . $attachmentLink . '</td>
                        <td>' . $row->oleh . '</td>
                        <td> 
                        <a href="' . route('show.media', $row->id) . '" class="btn btn-success btn-sm m-1"><i class="fas fa-edit" style="color: #ffffff;"></i> Edit</a>
                        <a href="' . route('delete.media', $row->id) . '" data-confirm-delete="true" class="btn btn-danger btn-sm"><i class="fas fa-trash" style="color: #ffffff;"></i> Hapus</a>
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

    public function downloadCover($cover)
    {
        return response()->download(storage_path('app/public/media/' . $cover));
    }
    public function downloadDoc($doc)
    {
        return response()->download(storage_path('app/public/attachment/' . $doc));
    }


    public function create()
    {
        return view('media.tambah');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_media' => 'required',
            'headline' => 'required',
            'no_volume' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'keterangan' => 'required|string|max:255',
            'cover' => 'file|mimes:jpg,jpeg,png',
            'attachment' => 'file|mimes:pdf,doc,docx',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Inisialisasi nama file dengan nilai default null
        $coverName = null;
        $attachmentName = null;

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = $attachment->getClientOriginalName();

            // Cek duplikasi nama file attachment
            if (Media::where('attachment', $attachmentName)->exists()) {
                toast('Dokumen sudah ada', 'error')->autoClose(3000)->timerProgressBar();
                return redirect()->back()->withInput();
            }

            // Simpan attachment
            $attachment->storeAs('attachment', $attachmentName, 'public');
        }

        // Handle file cover
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverName = $cover->getClientOriginalName();

            // Cek duplikasi nama file cover
            if (Media::where('cover', $coverName)->exists()) {
                toast('Cover sudah ada', 'error')->autoClose(3000)->timerProgressBar();
                return redirect()->back()->withInput();
            }

            // Simpan cover
            $cover->storeAs('media', $coverName, 'public');
        }

        // Buat objek Media baru dan isi dengan data dari request
        $media = new Media();
        $media->nama_media = $request->nama_media;
        $media->headline = $request->headline;
        $media->no_volume = $request->no_volume;
        $media->tahun = $request->tahun;
        $media->keterangan = $request->keterangan;
        $media->oleh = "Administrator";

        // Simpan informasi file dalam database
        $media->cover = $coverName;
        $media->attachment = $attachmentName;

        // Simpan media ke database
        $media->save();

        // Tampilkan pemberitahuan sukses
        toast('Berhasil menambahkan media', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/media');
    }


    // public function getKategori(Request $request)
    // {
    //     $id_kategori = [];
    //     if ($search = $request->name) {
    //         $id_kategori = KategoriAturan::where('name', 'like', "%$search%")->get();
    //     }
    //     return response()->json($id_kategori);
    // }

    public function show($id)
    {
        $media = Media::where('id', $id)->get();
        // $jenis = JenisAturan::get();
        // $kategori =  KategoriAturan::get();
        return view('media.edit', compact('media'));
    }

    public function update(Request $request, $id)
    {
        // Buat objek Aturan baru dan isi dengan data dari request
        $media = Media::where('id', $id)->first();
        $attachmentName = $media->attachment;
        $coverName = $media->cover;
        if ($request->hasFile('attachment')) {
            // Hapus file lama
            Storage::disk('public')->delete('attachment/' . $attachmentName);
            $attachment = $request->file('attachment');
            //tambah file baru
            $attachmentName = $attachment->getClientOriginalName();
            $attachment->storeAs('attachment', $attachmentName, 'public');
        }
        if ($request->hasFile('cover')) {
            // Hapus file lama
            Storage::disk('public')->delete('media/' . $coverName);
            $cover = $request->file('cover');
            //tambah file baru
            $coverName = $cover->getClientOriginalName();
            $cover->storeAs('media', $coverName, 'public');
        }
        $media->update([
            'nama_media' => $request->nama_media,
            'headline' => $request->headline,
            'no_volume' => $request->no_volume,
            'tahun' => $request->tahun,
            'keterangan' => $request->keterangan,
            'oleh' => "Administrator",
            'cover' => $coverName,
            'attachment' => $attachmentName,
        ]);
        toast('Berhasil mengubah media', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/media');
    }

    public function destroy($id)
    {
        $media = Media::where('id', $id)->first();
        Storage::disk('public')->delete('media/' . $media->cover);
        Storage::disk('public')->delete('attachment/' . $media->attachment);
        $media->delete();

        toast('Berhasil menghapus media', 'success')->autoClose(3000)->timerProgressBar();
        return back();
    }
}
