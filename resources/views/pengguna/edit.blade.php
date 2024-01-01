@extends('layout.home')
@section('content')
<div class="container-fluid">
    <div class="col d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengguna</h1>
    </div>
    <div class="col col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 ">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Edit Pengguna
                        </div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
                @foreach($users as $u)
                <form class="row g-3" method="POST" action="{{ route('update.pengguna',$u->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label for="inputNomor" class="form-label">Username</label>
                        <input value="{{ $u->username }}" name="username" type="text" class="form-control" id="inputNomor" placeholder="Username">
                    </div>
                    <div class="col-md-6">
                        <label for="inputTahun" class="form-label">Nama Akun</label>
                        <input value="{{ $u->account_name }}" name="account_name" type="text" class="form-control" id="inputTahun" placeholder="Nama Akun">
                    </div>
                    <div class="col-12">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input value="{{ $u->email }}" name="email" type="text" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                    
                    
                    <div class="col-12">
                        <label for="selectLevel" class="form-label">Level</label>
                        <select name="level" class="form-select" aria-label="Default select example" id="selectLevel">
                            <option >--Pilih Level--</option>
                            <option value="1" {{ $u->level==1 ? 'Selected':'' }}>Admin</option>
                            <option value="5" {{ $u->level==5 ? 'Selected':'' }}>Pengguna</option>
                          </select>
                    </div>
                    

                    <div class="col-md-6">
                        <button class="btn btn-primary my-2">Ubah </button>
                        <a class="btn btn-secondary" href="{{ route('index.pengguna') }}">
                            Batal
                        </a>
                    </div>

                </form>
                @endforeach
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