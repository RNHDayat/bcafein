<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::paginate(8);
        $title = 'Hapus pengguna!';
        $text = "Apakah anda yakin ingin menghapus ini?";
        confirmDelete($title, $text);
        return view('pengguna.pengguna', compact('pengguna'));
    }

    function action(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            $perPage = 10; // Anda dapat mengatur jumlah data per halaman sesuai kebutuhan

            if ($query != '') {
                $data = User::where('email', 'like', '%' . $query . '%')
                    ->orWhere('username', 'like', '%' . $query . '%')
                    ->paginate($perPage);
            } else {
                $data = User::paginate($perPage);
            }

            $total_row = $data->total();
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $level = $row->level == 1 ? 'Admin' : 'Pengguna';
                    $output .= '
                        <tr>
                        <td>' . ($data->firstItem() + $index) . '</td>
                        <td>' . $row->username . '</td>
                        <td>' . $row->account_name . '</td>
                        <td>' . $row->email . '</td>
                        <td>' . $level . '</td>
                        <td> 
                            <a href="' . route('show.pengguna', $row->id) . '" class="btn btn-success btn-sm m-1"><i class="fas fa-edit" style="color: #ffffff;"></i> Edit</a>
                            <a href="' . route('delete.pengguna', $row->id) . '" data-confirm-delete="true" class="btn btn-danger btn-sm"><i class="fas fa-trash" style="color: #ffffff;"></i> Hapus</a>
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
        return view('pengguna.tambah');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'account_name' => 'required',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => 'required',
            'level' => 'required',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if (User::where('email', $request->email)->exists()) {
            toast('Akun sudah ada', 'error')->autoClose(3000)->timerProgressBar();
            return redirect()->back()->withInput();
        }

        // Buat objek Media baru dan isi dengan data dari request
        $password = Hash::make($request->password);
        $users = new User();
        $users->username = $request->username;
        $users->account_name = $request->account_name;
        $users->email = $request->email;
        $users->password = $password;
        $users->status = 1;
        $users->level = $request->level;
        $users->save();

        // Tampilkan pemberitahuan sukses
        toast('Berhasil menambahkan pengguna', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/pengguna');
    }

    public function show($id)
    {
        $users = User::where('id', $id)->get();
        // $jenis = JenisAturan::get();
        // $kategori =  KategoriAturan::get();
        return view('pengguna.edit', compact('users'));
    }

    public function update(Request $request, $id)
    {
        // Buat objek Aturan baru dan isi dengan data dari request
        $media = User::where('id', $id)->first();

        $media->update([
            'username' => $request->username,
            'account_name' => $request->account_name,
            'email' => $request->email,
            'level' => $request->level,

        ]);
        toast('Berhasil mengubah pengguna', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/pengguna');
    }

    public function destroy($id)
    {
        $users = User::where('id', $id)->delete();
        $users = Employee::where('id_user', $id)->delete();
        toast('Berhasil menghapus pengguna', 'success')->autoClose(3000)->timerProgressBar();
        return back();
    }
}
