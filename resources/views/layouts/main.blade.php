<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="response-message" content="{{ session('response-message') }}">
    <meta name="response-status" content="{{ session('response-status') }}">
    <title>@yield('page-title', 'QR Code Absen')</title>
    <link rel="icon" type="image/x-icon" href="/images/panenqu_sm.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Sweetalert2 -->
    {{-- <link rel="stylesheet" href="/assets/adminlte/plugins/sweetalert2/sweetalert2.min.css"> --}}
    <link rel="stylesheet" href="/assets/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    {{-- Toasttr --}}
    <link rel="stylesheet" href="/assets/adminlte/plugins/toastr/toastr.min.css">
    @yield('page-css')
    <style>
        :root {
            --primary-color: #EE632C;
        }

        .w10px {
            width: 10px;
        }

        .w20px {
            width: 20px;
        }

        .w30px {
            width: 30px;
        }

        .w40px {
            width: 40px;
        }

        .w50px {
            width: 50px;
        }

        .w60px {
            width: 60px;
        }

        .w70px {
            width: 70px;
        }

        .w80px {
            width: 80px;
        }

        .w90px {
            width: 90px;
        }

        .w100px {
            width: 100px;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
        .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            box-shadow: none;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .rating-container .caption {
            display: none;
        }

        .rating-container .filled-stars {
            color: var(--primary-color) !important;
            -webkit-text-stroke: unset !important;
            text-shadow: unset !important;
        }
    </style>
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/adminlte/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-navbar-fixed layout-fixed">
    <div class="wrapper">

        @include('layouts.header')
        @include('layouts.sidebar')

        @yield('content')

        @include('layouts.footer')

    </div>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script type="text/javascript" src="/assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script type="text/javascript" src="/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Sweetalert2 -->
    <script type="text/javascript" src="/assets/adminlte/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    {{-- Toastr --}}
    <script type="text/javascript" src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
    <script type="text/javascript">
        function showLoading(message = '') {
            Swal.fire({
                title: 'Please Wait',
                html: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                width: '300px',
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let url = location.href;
            let pathname = location.pathname;
            $('.nav-sidebar .nav-link').each(function(i, v) {
                let thisHref = $(v).attr('href');
                let indexOf = url.indexOf(thisHref);
                if ($(v).hasClass('exact')) {
                    if (thisHref == url.slice(0, -1)) {
                        $(v).addClass('active');
                        return false;
                    }
                }

                if (indexOf !== -1 && !$(v).hasClass('exact') && thisHref != '#') {
                    $(v).addClass('active');
                    let hasTreeView = $(v).parents('ul.nav-treeview').length;
                    if (hasTreeView > 0) {
                        $(v).parents('ul.nav-treeview').prev('a.nav-link').addClass('active').parent()
                            .addClass(
                                'menu-open');
                        $(v).parents('ul.nav-treeview').find('a.nav-link').removeClass('active');
                        $(v).addClass('active');
                    }
                }
            });
        });
    </script>
    @yield('page-js')
    <!-- AdminLTE App -->
    <script type="text/javascript" src="/assets/adminlte/js/adminlte.min.js"></script>
</body>

</html>
