@extends('layout.home')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="mb-3">
        <h1 class="h3 mb-0 text-gray-800">Ruang</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Ruang</h6>
                </div>

                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between mb-3">
                        <a href="{{ route('create.ruang') }}" class="btn btn-primary"><i class="fas fa-pen fa-sm text-white-50 px-1"></i> Tambah Ruang</a>
                        <div>
                            <input type="search" class="form-control" name="search" id="searchRuang" placeholder="Search">
                        </div>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode Ruang</th>
                                <th scope="col">Nama Ruang</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-ruang"></tbody>
                    </table>

                    <!-- Pagination Placeholder -->
                    <div id="pagination-link-ruang"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

<!-- Add your scripts here -->
