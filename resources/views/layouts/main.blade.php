<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RKS | @yield('title', 'Layouts')</title>



    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('mazer/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

</head>

<body>
    <script src="{{ asset('mazer/static/js/initTheme.js') }}"></script>
    <div id="app">
        {{-- sidebar --}}
        @include('layouts.sidebar')

        <div id="main" class='layout-navbar navbar-fixed'>

            {{-- navbar --}}
            @include('layouts.navbar')

            <div id="main-content">
                @yield('content')
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2023 &copy; Mazer</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                            by <a href="https://saugi.me">Saugi</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ asset('mazer/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('mazer/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('mazer/compiled/js/app.js') }}"></script>

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- toast --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    {{-- swal --}}
    <script src="{{ asset('mazer/extensions/sweetalert2/sweetalert2.min.js') }}"></script>>
    <script src="{{ asset('mazer/static/js/pages/sweetalert2.js') }}"></script>>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>


    <!-- Need: Apexcharts -->
    <script src="{{ asset('mazer/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('mazer/static/js/pages/dashboard.js') }}"></script>

    {{-- Tooltip --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);
    </script>

    @yield('scripts')

</body>

</html>
