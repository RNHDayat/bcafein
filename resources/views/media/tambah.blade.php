@extends('layout.home')
@section('content')
<div class="container-fluid">
    <div class="col d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Media</h1>

    </div>

    <div class="col col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 ">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tambah Media
                        </div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
                <form class="row g-3" method="POST" action="{{ route('store.media') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label for="inputNomor" class="form-label">Nama Media</label>
                        <input name="nama_media" type="text" class="form-control" id="inputNomor" placeholder="Nomor">
                    </div>
                    <div class="col-md-6">
                        <label for="inputTahun" class="form-label">Headline</label>
                        <input name="headline" type="text" class="form-control" id="inputTahun" placeholder="Tahun">
                    </div>
                    <div class="col-md-6">
                        <label for="inputNomor" class="form-label">No Volume</label>
                        <input name="no_volume" type="text" class="form-control" id="inputNomor" placeholder="Nomor">
                    </div>
                    <div class="col-md-6">
                        <label for="inputTahun" class="form-label">Tahun</label>
                        <input name="tahun" type="text" class="form-control" id="inputTahun" placeholder="Tahun">
                    </div>
                    
                    <div class="col-12">
                        <label for="exampleFormControlTextarea1" class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" id="exampleFormControlTextarea1"
                            rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="formFile" class="form-label">Cover</label>
                        <input name="cover" class="form-control" type="file" id="formFile">
                    </div>
                    <div class="col-12 mb-2">
                        <label for="formFile" class="form-label">File Dokumen</label>
                        <input name="attachment" class="form-control" type="file" id="formFile">
                    </div>

                    <div class="col-md-6">
                        <button class="btn btn-primary my-2">Tambah </button>
                        <a class="btn btn-secondary" href="{{ route('index.media') }}">
                            Batal
                        </a>
                    </div>

                </form>
                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


            </div>
        </div>
    </div>
</div>
@endsection