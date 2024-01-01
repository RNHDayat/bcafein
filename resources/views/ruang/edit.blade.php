@extends('layout.home')
@section('content')
<div class="container-fluid">
    <div class="col d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ruang</h1>

    </div>
    <div class="col">
        <a href="{{ route('index.ruang') }}">
            <h6>Back</h6>
        </a>
    </div>
    <div class="col col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 ">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tambah Data Ruang
                        </div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
                @foreach($ruang as $room)
                <form method="POST" action="{{ route('update.ruang',$room->id) }}">
                    @csrf
                    <div class="py-3">
                        <input type="text" name="name" class="form-control" placeholder="Nama Ruang"
                            aria-label="Nama Ruang" value="{{ $room->name }}">
                    </div>
                    <button class="btn btn-primary">Ubah </button>
                </form>
                @endforeach
                

            </div>
        </div>
    </div>
</div>
@endsection