<?php

namespace App\Http\Controllers;

use App\Models\JenisAturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisController extends Controller
{
    public function index()
    {
        $jenis = JenisAturan::paginate(8);
        $title = 'Hapus Jenis Aturan!';
        $text = "Apakah anda yakin ingin menghapus ini?";
        confirmDelete($title, $text);
        return view('jenis.jenis', compact('jenis'));
    }

    function action(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            $perPage = 10; // Anda dapat mengatur jumlah data per halaman sesuai kebutuhan

            if ($query != '') {
                $data = JenisAturan::where('name', 'like', '%' . $query . '%')
                    ->orWhere('keterangan', 'like', '%' . $query . '%')
                    ->paginate($perPage);
            } else {
                $data = JenisAturan::paginate($perPage);
            }

            $total_row = $data->total();
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $output .= '
                <tr>
                <td>' . ($data->firstItem() + $index) . '</td>
                <td>' . $row->name . '</td>
                <td>' . $row->keterangan . '</td>
                <td> 
                    <a href="' . route('show.jenis', $row->id) . '" class="btn btn-success"><i class="fas fa-edit" style="color: #ffffff;"></i> Edit</a>
                    <a href="' . route('delete.jenis', $row->id) . '" data-confirm-delete="true" class="btn btn-danger"><i class="fas fa-trash" style="color: #ffffff;"></i> Hapus</a>
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
        return view('jenis.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255|string|unique:jenis,name',
            'keterangan' => 'required|min:2|max:255|string|unique:jenis,keterangan',
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        JenisAturan::create([
            'name' => $request->name,
            'keterangan' => $request->keterangan,
            'oleh' => 'Administrator'
        ]);

        toast('Berhasil menambahkan jenis aturan', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/jenis');
    }


    public function show($id)
    {
        $jenis = JenisAturan::where('id', $id)->get();
        return view('jenis.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        JenisAturan::where('id', $id)->update([
            "name" => $request->name,
            "keterangan" => $request->keterangan,
        ]);
        toast('Berhasil mengubah jenis aturan', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/jenis');
    }

    public function destroy($id)
    {
        $jenis= JenisAturan::where('id', $id)->delete();
        toast('Berhasil menghapus jenis', 'success')->autoClose(3000)->timerProgressBar();
        return back();
    }
}
