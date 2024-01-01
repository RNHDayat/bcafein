@extends('layout.home')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="mb-3">
        <h1 class="h3 mb-0 text-gray-800">Aturan</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Aturan Regulasi</h6>
                </div>
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between mb-3">
                        <a href="{{ route('create.aturan') }}" class="btn btn-primary"><i class="fas fa-pen fa-sm text-white-50 px-1"></i> Tambah Aturan</a>
                        <div>
                            <input type="search" class="form-control" name="search" id="searchAturan" placeholder="Search">
                        </div>
                    </div>

                    <table class="table table-bordered table-sm table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tentang</th>
                                <th scope="col">Jenis</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Nomor</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Nama File</th>
                                <th scope="col">Oleh</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-aturan"></tbody>
                        {{-- @foreach($aturan as $a)
                        <tbody>
                            <tr>
                                <td>{{ $a->short_desc }}</td>
                                {{-- <td>{{ $aturan->short_desc }}</td>
                                <td>{{ $aturan->short_desc }}</td>
                                <td>{{ $aturan->short_desc }}</td>
                                <td>{{ $aturan->short_desc }}</td>
                                <td>{{ $aturan->short_desc }}</td>
                                <td>{{ $aturan->short_desc }}</td>
                                <td>{{ $aturan->short_desc }}</td>
                                <td>{{ $aturan->short_desc }}</td> --}}
                            {{-- </tr>
                        </tbody>
                        @endforeach --}} 
                    </table>

                    <!-- Pagination Placeholder -->
                    <div id="pagination-link-aturan"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

<!-- Add your scripts here -->
