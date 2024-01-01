@extends('layout.home')
@section('content')
<div class="container-fluid">
    <div class="col d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Aturan</h1>

    </div>

    <div class="col col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 ">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tambah Aturan
                        </div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
                <form class="row g-3" method="POST" action="{{ route('store.aturan') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label for="" class="form-label">Jenis Peraturan</label>
                        <select name="id_jenis" class="form-select" aria-label="Default select example">
                            <option selected>--Pilih Jenis--</option>
                            @foreach($jenis as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="select2Multiple">Kategori</label>
                        <select class="select2-multiple form-control" name="id_kategori[]" multiple="multiple"
                          id="select2Multiple">
                          @foreach($kategori as $k)
                          <option  value="{{ $k->id }}:{{ $k->name }}">{{ $k->name }}</option>
                          @endforeach        
                        </select>
                      </div>
          
                    <div class="col-md-6">
                        <label for="inputNomor" class="form-label">Nomor</label>
                        <input name="nomor" type="text" class="form-control" id="inputNomor" placeholder="Nomor">
                    </div>
                    <div class="col-md-6">
                        <label for="inputTahun" class="form-label">Tahun</label>
                        <input name="tahun" type="text" class="form-control" id="inputTahun" placeholder="Tahun">
                    </div>
                    <div class="col-12">
                        <label for="inputTentang" class="form-label">Tentang</label>
                        <input name="short_desc" type="text" class="form-control" id="inputTentang">
                    </div>
                    <div class="col-12">
                        <label for="exampleFormControlTextarea1" class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" id="exampleFormControlTextarea1"
                            rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">File Dokumen</label>
                        <input name="doc" class="form-control" type="file" id="formFile">
                    </div>

                    <div class="col-md-6">
                        <button class="btn btn-primary">Tambah </button>
                        <a class="btn btn-secondary" href="{{ route('index.aturan') }}">
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