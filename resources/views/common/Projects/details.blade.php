@extends('layouts.main')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Informasi Proyek -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="card-title text-primary fw-bold">{{ $project->title }}</h3>
                        <p class="card-text text-muted ">
                            <strong>Deskripsi:</strong>
                            {{ \Illuminate\Support\Str::limit($project->description, 150, '...') }}
                            <a href="#" data-bs-toggle="modal" data-bs-target="#descriptionModal">Lihat Selengkapnya</a>
                        </p>

                        <!-- Modal -->
                        <div class="modal fade modal-xl" id="descriptionModal" tabindex="-1"
                            aria-labelledby="descriptionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white rounded-top">
                                        <h5 class="modal-title" id="descriptionModalLabel">Deskripsi Proyek</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body bg-body-secondary text-body">
                                        {{ $project->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 class="fw-bold text-secondary">Anggota</h5>
                        <ul class="list-group list-group-flush">
                            @foreach ($project->members as $member)
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="avatar bg-primary me-3"
                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ asset('storage/' . $member->avatar) }}" alt="">
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">{{ $member->name }}</p>
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                        <h5 class="fw-bold text-secondary">Tugas</h5>
                        <!-- Form Pencarian -->
                        <form action="{{ route('projects.show', $project->id) }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari tugas..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </form>
                        {{-- daftar tugas --}}
                        <ul class="list-group" id="taskList">
                            @forelse ($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center task-item">
                                    <div>
                                        <p class="mb-0 fw-bold">
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="text-decoration-none text-success">
                                                {{ $task->title }}
                                            </a>
                                        </p>
                                        <small class="text-muted">{{ $task->description }}</small>
                                    </div>
                                    <span
                                        class="badge rounded-pill
                                                {{ $task->status == 'in_progress' ? 'bg-warning' : '' }}
                                                {{ $task->status == 'completed' ? 'bg-success' : '' }}
                                                {{ $task->status == 'pending' ? 'bg-secondary' : '' }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Tidak ada tugas ditemukan.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar Tambah Tugas untuk leader-->
            @if (Auth::user()->role === 'leader')
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <button class="btn btn-success w-100 mb-3" data-bs-toggle="modal"
                                data-bs-target="#addTaskModal">
                                Tambah Tugas
                            </button>
                            <h5 class="fw-bold text-secondary">Panduan</h5>
                            <p class="text-muted">
                                Gunakan tombol di atas untuk menambahkan tugas baru ke proyek ini.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Modal tambah tugas -->
    <div class="modal fade modal-lg" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content rounded-4 shadow-lg">
                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white rounded-top">
                    <i class="bi bi-plus-circle-fill me-2 mb-2"></i>
                    <h5 class="modal-title text-white">
                        Tambah Tugas Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body bg-body-secondary text-body">
                    <form action="/tasks" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <!-- Task Title -->
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-light">
                                    <i class="bi bi-pencil mb-2"></i>
                                </span>
                                <input type="text" class="form-control" id="taskTitle" name="title"
                                    placeholder="Masukkan judul tugas" required>
                            </div>
                        </div>

                        <!-- Task Description -->
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Deskripsi Tugas <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control rounded-3" id="taskDescription" name="description" rows="3"
                                placeholder="Deskripsikan tugas secara detail" required></textarea>
                        </div>

                        <!-- Assigned To -->
                        <div class="mb-3">
                            <label for="assignedTo" class="form-label">Ditugaskan Kepada <span
                                    class="text-danger">*</span></label>
                            <select class="form-select choices" id="assignedTo" name="assigned_to" required>
                                <option value="" selected disabled>Pilih anggota</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Due Date -->
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Tanggal Batas Akhir <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-light">
                                    <i class="bi bi-calendar-date mb-2"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="due_date" name="due_date"
                                    placeholder="Pilih tanggal batas akhir" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <input type="text" id="status" name="status" value="pending" style="display: none">

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary rounded-3 py-2">
                                <i class="bi bi-check-circle-fill me-2 mb-4"></i> Tambah Tugas
                            </button>
                        </div>
                    </form>
                </div>
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
