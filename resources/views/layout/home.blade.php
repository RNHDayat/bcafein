<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Admin</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}"
        rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        {{-- selec2 cdn --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('sweetalert::alert')

        <!-- Sidebar -->
        <div id="sticky-sidebar">
            <div class="sticky-top">
                <ul class="navbar-nav  bg-gradient-primary sidebar sidebar-dark accordion " id="accordionSidebar">

                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-tools"></i>
                        </div>
                        
                        <div class="sidebar-brand-text mx-3">Admin</div>
                    </a>
                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <!-- Nav Item - Dashboard -->
                    <li
                        class="nav-item {{ Route::is('index.ruang') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('index.ruang') }}">
                            <i class="fas fa-home"></i>
                            <span>Ruang</span></a>
                    </li>
                    <li
                        class="nav-item {{ Route::is('index.jenis') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('index.jenis') }}">
                            
                            <i class="fas fa-table"></i>
                            <span>Jenis</span></a>
                    </li>
                    <li
                        class="nav-item {{ Route::is('index.kategori') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('index.kategori') }}">
                            
                            <i class="fas fa-table"></i>
                            <span>Kategori</span></a>
                    </li>
                    <li
                        class="nav-item {{ Route::is('index.aturan') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('index.aturan') }}">
                            <i class="fas fa-table"></i>
                            <span>Aturan</span></a>
                    </li>
                    <li
                        class="nav-item {{ Route::is('index.media') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('index.media') }}">
                            <i class="fas fa-photo-video"></i>
                            <span>Media</span></a>
                    </li>
                    <li
                        class="nav-item {{ Route::is('index.pengguna') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('index.pengguna') }}">
                            <i class="fas fa-users"></i>
                            <span>Pengguna</span></a>
                    </li>
                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                  
                    <!-- Divider -->

                    <li class="nav-item ">
                        <a class="nav-link" data-toggle="modal" data-target="#logoutModal" href="">
                            <i class="fas fa-sign-out-alt" style="color: red;"></i>
                            <span style="color: red;">Logout</span></a>
                    </li>
                    <hr class="sidebar-divider d-none d-md-block">
                    <!-- Sidebar Toggler (Sidebar) -->
                    <div class="text-center d-none d-md-inline">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>
                </ul>
            </div>
        </div>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column py-4">
            <!-- Main Content -->
            <div id="content">

                @yield('content')
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Peringatan!</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin logout?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('include.script')
</body>

</html>