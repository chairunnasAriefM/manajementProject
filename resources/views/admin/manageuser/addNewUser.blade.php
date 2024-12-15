@extends('layouts.main')

@section('title', 'Tambah User')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="fw-bold">Tambah Pengguna Baru</h3>
            </div>

            <div class="card-body mt-3">
                {{-- Menampilkan pesan error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('addnewuser.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Nama</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="bi bi-person mb-2"></i>
                            </span>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Masukkan nama" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="bi bi-envelope mb-2"></i>
                            </span>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Masukkan email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="bi bi-lock mb-2"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="bi bi-lock mb-2"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Konfirmasi password" required>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="role" class="form-label fw-bold">Pilih Role</label>
                        <select name="role" id="role" class="choices form-select" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                            <option value="leader" {{ old('role') == 'leader' ? 'selected' : '' }}>Leader</option>
                            <option value="programmer" {{ old('role') == 'programmer' ? 'selected' : '' }}>Programmer
                            </option>
                            <option value="3dArtist" {{ old('role') == '3dArtist' ? 'selected' : '' }}>3D Artist</option>
                            <option value="2dArtist" {{ old('role') == '2dArtist' ? 'selected' : '' }}>2D Artist</option>
                            <option value="music composer" {{ old('role') == 'music composer' ? 'selected' : '' }}>Music
                                Composer</option>
                        </select>
                    </div>

                    <!-- Avatar -->
                    <div class="mb-4">
                        <label for="avatar" class="form-label fw-bold">Upload Avatar</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="bi bi-image mb-2"></i>
                            </span>
                            <input type="file" name="avatar" id="avatar" class="form-control">
                        </div>
                        <small class="text-muted">File berupa gambar (jpg, jpeg, png) maksimal 2MB.</small>
                    </div>


                    <!-- Tombol Submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Tambah User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Choices('#role', {
                searchEnabled: true,
                placeholderValue: 'Cari Role...',
                noResultsText: 'Tidak ada hasil',
                itemSelectText: 'Pilih',
            });
        });
    </script>
@endsection
