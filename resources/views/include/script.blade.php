<!-- Bootstrap core JavaScript-->
<script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<!-- Core plugin JavaScript-->
<script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('admin_assets/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('admin_assets/js/demo/chart-pie-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Select2 Multiple
        $('.select2-multiple').select2({
            placeholder: "Select",
            allowClear: true
        });

    });
</script>

{{-- ruang --}}
<script type="text/javascript">
    $(document).ready(function () {
        fetch_customer_data();

        $(document).on('keyup', '#searchRuang', function () {
            var query = $(this).val();
            fetch_customer_data(query);
        });

        // Menargetkan tautan di dalam #pagination-link
        $(document).on('click', '#pagination-link-ruang a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_customer_data($('#searchRuang').val(), page);
        });

        function fetch_customer_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('action.ruang') }}?page=" + page,
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                success: function (data) {
                    $('#data-ruang').html(data.table_data);
                    $('#pagination-link-ruang').html(data.pagination);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                }
            });
        }
    });
</script>

{{-- jenis --}}
<script type="text/javascript">
    $(document).ready(function () {
        fetch_customer_data();

        $(document).on('keyup', '#searchJenis', function () {
            var query = $(this).val();
            fetch_customer_data(query);
        });

        // Menargetkan tautan di dalam #pagination-link
        $(document).on('click', '#pagination-link-jenis a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_customer_data($('#searchJenis').val(), page);
        });

        function fetch_customer_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('action.jenis') }}?page=" + page,
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                success: function (data) {
                    $('#data-jenis').html(data.table_data);
                    $('#pagination-link-jenis').html(data.pagination);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                }
            });
        }
    });
</script>

{{-- kategori --}}
<script type="text/javascript">
    $(document).ready(function () {
        fetch_customer_data();

        $(document).on('keyup', '#searchKategori', function () {
            var query = $(this).val();
            fetch_customer_data(query);
        });

        // Menargetkan tautan di dalam #pagination-link
        $(document).on('click', '#pagination-link-kategori a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_customer_data($('#searchKategori').val(), page);
        });

        function fetch_customer_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('action.kategori') }}?page=" + page,
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                success: function (data) {
                    $('#data-kategori').html(data.table_data);
                    $('#pagination-link-kategori').html(data.pagination);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                }
            });
        }
    });
</script>


{{-- aturan --}}
<script type="text/javascript">
    $(document).ready(function () {
        fetch_customer_data();

        $(document).on('keyup', '#searchAturan', function () {
            var query = $(this).val();
            fetch_customer_data(query);
        });

        // Menargetkan tautan di dalam #pagination-link
        $(document).on('click', '#pagination-link-aturan a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_customer_data($('#searchAturan').val(), page);
        });

        function fetch_customer_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('action.aturan') }}?page=" + page,
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                success: function (data) {
                    $('#data-aturan').html(data.table_data);
                    $('#pagination-link-aturan').html(data.pagination);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                }
            });
        }
    });
</script>

{{-- media --}}
<script type="text/javascript">
    $(document).ready(function () {
        fetch_customer_data();

        $(document).on('keyup', '#searchMedia', function () {
            var query = $(this).val();
            fetch_customer_data(query);
        });

        // Menargetkan tautan di dalam #pagination-link
        $(document).on('click', '#pagination-link-media a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_customer_data($('#searchMedia').val(), page);
        });

        function fetch_customer_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('action.media') }}?page=" + page,
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                success: function (data) {
                    $('#data-media').html(data.table_data);
                    $('#pagination-link-media').html(data.pagination);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                }
            });
        }
    });
</script>

{{-- pengguna --}}
<script type="text/javascript">
    $(document).ready(function () {
        fetch_customer_data();

        $(document).on('keyup', '#searchPengguna', function () {
            var query = $(this).val();
            fetch_customer_data(query);
        });

        // Menargetkan tautan di dalam #pagination-link
        $(document).on('click', '#pagination-link-pengguna a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_customer_data($('#searchPengguna').val(), page);
        });

        function fetch_customer_data(query = '', page = 1) {
            $.ajax({
                url: "{{ route('action.pengguna') }}?page=" + page,
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                success: function (data) {
                    $('#data-pengguna').html(data.table_data);
                    $('#pagination-link-pengguna').html(data.pagination);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                }
            });
        }
    });
</script>
