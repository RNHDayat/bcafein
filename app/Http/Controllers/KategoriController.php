<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriAturan;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriAturan::paginate(8);
        $title = 'Hapus Kategori Aturan!';
        $text = "Apakah anda yakin ingin menghapus ini?";
        confirmDelete($title, $text);
        return view('kategori.kategori', compact('kategori'));
    }

    function action(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            $perPage = 10; // Anda dapat mengatur jumlah data per halaman sesuai kebutuhan

            if ($query != '') {
                $data = KategoriAturan::where('name', 'like', '%' . $query . '%')
                    ->paginate($perPage);
            } else {
                $data = KategoriAturan::paginate($perPage);
            }

            $total_row = $data->total();
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $output .= '
                <tr>
                <td>' . ($data->firstItem() + $index) . '</td>
                <td>' . $row->name . '</td>
                <td> 
                    <a href="' . route('show.kategori', $row->id) . '" class="btn btn-success"><i class="fas fa-edit" style="color: #ffffff;"></i> Edit</a>
                    <a href="' . route('delete.kategori', $row->id) . '" data-confirm-delete="true" class="btn btn-danger"><i class="fas fa-trash" style="color: #ffffff;"></i> Hapus</a>
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

    public function create()
    {
        return view('kategori.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255|string|unique:kategori,name',
            
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        KategoriAturan::create([
            'id_jenis' => 0,
            'name' => $request->name,
            'oleh' => 'Administrator'
        ]);

        toast('Berhasil menambahkan kategori aturan', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/kategori');
    }


    public function show($id)
    {
        $kategori = KategoriAturan::where('id', $id)->get();
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        KategoriAturan::where('id', $id)->update([
            "name" => $request->name,
        ]);
        toast('Berhasil mengubah kategori aturan', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/kategori');
    }

    public function destroy($id)
    {
        $kategori= KategoriAturan::where('id', $id)->delete();
        toast('Berhasil menghapus kategori', 'success')->autoClose(3000)->timerProgressBar();
        return back();
    }
}
