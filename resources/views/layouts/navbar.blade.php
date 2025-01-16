<header>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0">
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                            data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <i class='bi bi-bell bi-sub fs-4'></i>
                            <span class="badge badge-notification bg-danger">{{ $unreadNotificationsCount }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                            aria-labelledby="dropdownMenuButton">
                            <li class="dropdown-header">
                                <h6>Notifikasi</h6>
                            </li>
                            @forelse ($notifications as $notification)
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center" href="{{ $notification->url ?? '#' }}">
                                        <div class="notification-icon bg-primary">
                                            <i class="bi bi-info-circle"></i>
                                        </div>
                                        <div class="notification-text ms-4">
                                            <p class="notification-title font-bold">{{ $notification->message }}</p>
                                            <p class="notification-subtitle font-thin text-sm">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="dropdown-item notification-item">
                                    <p class="text-center text-gray-500">Tidak ada notifikasi.</p>
                                </li>
                            @endforelse
                            {{-- <li>
                                <p class="text-center py-2 mb-0"><a href="#">Lihat
                                        semua notifikasi</a></p>
                            </li> --}}
                        </ul>
                    </li>

                </ul>
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{ explode(' ', Auth::user()->name)[0] }}
                                </h6>
                                <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->role }}</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img
                                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('mazer/compiled/jpg/1.jpg') }}">

                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem;">
                        <li>
                            <h6 class="dropdown-header">Halo, {{ Auth::user()->name }}!</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal">
                                <i class="icon-mid bi bi-person me-2"></i> Profil Saya
                            </a>
                        </li>
                        <hr class="dropdown-divider">
                        </li>
                        <li>

                            <a class="dropdown-item" href="javascript:void(0)" id="logoutButton" style=""
                                onmouseover="this.style.backgroundColor='#dc3545'; this.style.color='white';"
                                onmouseout="this.style.backgroundColor='transparent'; this.style.color='black';">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                Logout
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Modal untuk show profile-->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content shadow-lg rounded-4">
            <!-- Header -->
            <div class="modal-header border-0 py-3">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile Photo"
                        class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div>
                        <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 py-3">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" value="{{ Auth::user()->name }}"
                        disabled>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}"
                        disabled>
                </div>
                <div class="mb-3">
                    <label for="Role" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="Role" value="{{ Auth::user()->role }}"
                        disabled>
                </div>
            </div>

        </div>
    </div>

    <!-- Style -->
    <style>
        .modal-content {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header {
            background-color: #f8f9fa;
        }

        .modal-body {
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
        }
    </style>
</div>


<script>
    document.getElementById('logoutButton').addEventListener('click', function() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan keluar dari aplikasi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('logout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON
                            .stringify({}) // Bisa kirim data kosong, karena hanya memerlukan POST
                    })
                    .then(response => {
                        if (response.ok) {
                            // Jika logout berhasil, arahkan pengguna ke halaman login atau home
                            window.location.href =
                                '{{ route('login') }}'; // Ganti dengan route login jika ada
                        } else {
                            // Menangani kesalahan jika ada masalah
                            Swal.fire('Terjadi kesalahan!', 'Logout gagal.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Terjadi kesalahan!', 'Tidak dapat menghubungi server.', 'error');
                    });
            }
        });
    });
</script>
