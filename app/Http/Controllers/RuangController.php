<?php

namespace App\Http\Controllers;

use App\Models\knowField;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ruang = knowField::paginate(8);
        $title = 'Hapus Data!';
        $text = "Apakah anda yakin ingin menghapus ini?";
        confirmDelete($title, $text);
        return view('ruang.ruang', compact('ruang'));
    }
    function action(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            $perPage = 10; // Anda dapat mengatur jumlah data per halaman sesuai kebutuhan

            if ($query != '') {
                $data = knowField::where('name', 'like', '%' . $query . '%')
                    ->paginate($perPage);
            } else {
                $data = knowField::paginate($perPage);
            }

            $total_row = $data->total();
            if ($total_row > 0) {
                foreach ($data as $index => $row) {
                    $output .= '
                <tr>
                <td>' . ($data->firstItem() + $index) . '</td>
                <td>' . $row->codeIlmu . '</td>
                <td>' . $row->name . '</td>
                <td> 
                    <a href="' . route('show.ruang', $row->id) . '" class="btn btn-success"><i class="fas fa-edit" style="color: #ffffff;"></i> Edit</a>
                    <a href="' . route('delete.ruang', $row->id) . '" data-confirm-delete="true" class="btn btn-danger"><i class="fas fa-trash" style="color: #ffffff;"></i> Hapus</a>
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ruang.tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255|string|unique:know_fields,name',
        ], [
            'name.unique' => 'Nama sudah ada', // Custom error message
        ]);


        if ($validator->fails()) {
            // example:
            $errorString = implode(", ", $validator->errors()->all());
            toast($errorString, 'error')->autoClose(3000)->timerProgressBar();
            return redirect('/create')
                ->withInput();
        }

        $faker = Faker::create();
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $randomCode = '';
            for ($i = 0; $i < 5; $i++) {
                $randomCode .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Periksa apakah kode sudah ada
            $isDuplicate = knowField::where('codeIlmu', $randomCode)->exists();
        } while ($isDuplicate);

        // Jika kode unik, lanjutkan proses penyimpanan
        $knowledge_field = new knowField();
        $knowledge_field->codeIlmu = $randomCode;
        $knowledge_field->name = $request->name;
        $knowledge_field->id_user_propose = auth()->user()->id;
        $knowledge_field->validation = 1;
        $knowledge_field->save();
        toast('Berhasil menambahkan ruang', 'success')->autoClose(3000)->timerProgressBar();
        return redirect('/');

        // Merit System
        # Code here...
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ruang = knowField::where('id', $id)->get();
        return view('ruang.edit', compact('ruang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
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
        knowField::where('id', $id)->update([
            "name" => $request->name,
        ]);
        toast('Berhasil mengubah ruang', 'success')->autoClose(3000)->timerProgressBar();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = knowField::where('id', $id)->delete();
        // alert()->success('Hore!', 'Post Deleted Successfully');
        toast('Berhasil menghapus ruang', 'success')->autoClose(3000)->timerProgressBar();
        return back();
    }
}
