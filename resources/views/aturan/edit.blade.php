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
                @foreach($aturan as $aturan)
                    <form class="row g-3" method="POST" action="{{ route('update.aturan',$aturan->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label for="" class="form-label">Jenis Peraturan</label>
                            <select name="id_jenis" class="form-select" aria-label="Default select example">
                                <option selected>--Pilih Jenis--</option>
                                @foreach($jenis as $j)
                                    <option value="{{ $j->id }}"
                                        {{ $aturan->id_jenis === $j->id ? 'Selected':'' }}>
                                        {{ $j->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            @php
                                $idKategoriArray = json_encode($aturan->id_kategori, true);
                                $dataArray = json_decode($idKategoriArray, true);
                            @endphp
                            
                            <label for="select2Multiple">Kategori</label>
                            <select class="select2-multiple form-control" name="id_kategori[]" multiple="multiple"
                                id="select2Multiple">
                                @foreach($kategori as $k)
                                    {{-- Cek apakah id kategori ada di dataArray --}}
                                    @php
                                        $isSelected = collect($dataArray)->contains('id', strval($k->id));
                                    @endphp

                                    <option value="{{ $k->id }}:{{ $k->name }}"
                                        {{ $isSelected ? 'selected' : '' }}>
                                        {{ $k->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-6">
                            <label for="inputNomor" class="form-label">Nomor</label>
                            <input value="{{ $aturan->nomor }}" name="nomor" type="text" class="form-control"
                                id="inputNomor" placeholder="Nomor">
                        </div>
                        <div class="col-md-6">
                            <label for="inputTahun" class="form-label">Tahun</label>
                            <input value="{{ $aturan->tahun }}" name="tahun" type="text" class="form-control"
                                id="inputTahun" placeholder="Tahun">
                        </div>
                        <div class="col-12">
                            <label for="inputTentang" class="form-label">Tentang</label>
                            <input value="{{ $aturan->short_desc }}" name="short_desc" type="text"
                                class="form-control" id="inputTentang">
                        </div>
                        <div class="col-12">
                            <label for="exampleFormControlTextarea1" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" id="exampleFormControlTextarea1"
                                rows="3">{{ $aturan->keterangan }}</textarea>
                        </div>
                        <div class="">
                            <label for="formFile" class="form-label">File Dokumen</label>
                            <input value="{{ $aturan->doc }}" name="doc" class="form-control" type="file" id="formFile">
                        </div>
                        <a href="{{ route('download.aturan', $aturan->doc) }}"></i>
                            {{ $aturan->doc }}</a>

                        <div class="col-md-6">
                            <button class="btn btn-primary">Ubah </button>
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
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection