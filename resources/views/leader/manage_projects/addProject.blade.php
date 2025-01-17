@extends('layouts.main')

@section('title', 'Tambah Project')

<style>
    .input-group-text {
        padding: 0.375rem 0.75rem;
        font-size: 1.25rem;
        /* Ukuran ikon */
    }
</style>

@section('content')

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-light text-center">
                <h3 class="text-white">Tambah Proyek Baru</h3>
            </div>
            <div class="card-body mt-3">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <!-- Judul Proyek -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Judul Proyek</label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Masukkan judul proyek" required>
                    </div>

                    <!-- Deskripsi Proyek -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Deskripsi Proyek</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                            placeholder="Tambahkan deskripsi proyek"></textarea>
                    </div>

                    <div class="row">
                        <!-- Tanggal Mulai -->
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label fw-bold">Tanggal Mulai</label>
                            <div class="input-group">
                                <span
                                    class="input-group-text bg-primary text-white d-flex align-items-center justify-content-center">
                                    <i class="bi bi-calendar-date"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="start_date" name="start_date"
                                    placeholder="Pilih tanggal mulai" required>
                            </div>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label fw-bold">Tanggal Selesai</label>
                            <div class="input-group">
                                <span
                                    class="input-group-text bg-primary text-white d-flex align-items-center justify-content-center">
                                    <i class="bi bi-calendar-date"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="end_date" name="end_date"
                                    placeholder="Pilih tanggal selesai" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tambah Anggota Proyek -->

                    <div class="form-group">
                        <label for="members" class="form-label fw-bold">Anggota Proyek</label>
                        <select class="choices form-select multiple-remove" id="members" name="members[]"
                            multiple="multiple" required>
                            <optgroup label="Anggota">
                                @foreach ($users->filter(function ($user) {
            return !in_array($user->role, ['leader', 'admin']);
        }) as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                @endforeach

                            </optgroup>
                        </select>
                    </div>



                    <!-- Tombol Submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Simpan Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd F Y',
                allowInput: true
            });
        });
    </script>

@endsection
