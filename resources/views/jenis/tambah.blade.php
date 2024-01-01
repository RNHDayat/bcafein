@extends('layout.home')
@section('content')
<div class="container-fluid">
    <div class="col d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Jenis</h1>

    </div>
    
    <div class="col col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 ">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tambah Jenis Aturan
                        </div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
                <form method="POST" action="{{ route('store.jenis') }}">
                    @csrf
                    <div class="pt-3">
                        <input type="text" name="name" class="form-control" placeholder="Nama"
                            aria-label="Nama Jenis">
                    </div>
                    <div class="py-3">
                        <input type="text" name="keterangan" class="form-control" placeholder="Keterangan"
                            aria-label="keterangan">
                    </div>
                    <button class="btn btn-primary">Tambah </button>
                    <a class="btn btn-secondary" href="{{ route('index.jenis') }}">
                        Batal
                    </a>

                </form>
                @if($errors->any())
                    <div class="alert alert-danger mt-2">
                            @foreach($errors->all() as $error)
                                <span>{{ $error }}</span>
                            @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection