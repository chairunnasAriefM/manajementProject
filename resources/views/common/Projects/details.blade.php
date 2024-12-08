@extends('layouts.main')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Informasi Proyek -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="card-title text-primary fw-bold">{{ $project->title }}</h3>
                        <p class="card-text text-muted">
                            <strong>Deskripsi:</strong> {{ $project->description }}
                        </p>
                        <hr>
                        <h5 class="fw-bold text-secondary">Anggota</h5>
                        <ul class="list-group list-group-flush">
                            @foreach ($project->members as $member)
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle me-3"
                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
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
                        <ul class="list-group">
                            @foreach ($project->tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-0 fw-bold">
                                            <a href="#" class="text-decoration-none text-dark">{{ $task->title }}</a>
                                        </p>
                                        <small class="text-muted">{{ $task->description }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $task->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar Tambah Tugas -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <button class="btn btn-success w-100 mb-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            Tambah Tugas
                        </button>
                        <h5 class="fw-bold text-secondary">Panduan</h5>
                        <p class="text-muted">
                            Gunakan tombol di atas untuk menambahkan tugas baru ke proyek ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Task -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle-fill me-2"></i> Tambah Tugas Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="/tasks" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <!-- Task Title -->
                        <div class="mb-4">
                            <label for="taskTitle" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-pencil"></i></span>
                                <input type="text" class="form-control" id="taskTitle" name="title"
                                    placeholder="Masukkan judul tugas" required>
                            </div>
                        </div>

                        <!-- Task Description -->
                        <div class="mb-4">
                            <label for="taskDescription" class="form-label">Deskripsi Tugas <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="taskDescription" name="description" rows="4"
                                placeholder="Deskripsikan tugas secara detail" required></textarea>
                        </div>

                        <!-- Assigned To -->
                        <div class="mb-4">
                            <label for="assignedTo" class="form-label">Ditugaskan Kepada <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="assignedTo" name="assigned_to" required>
                                <option value="" selected disabled>Pilih anggota</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Due Date -->
                        <div class="mb-4">
                            <label for="dueDate" class="form-label">Tanggal Batas Akhir <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" id="dueDate" name="due_date" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label">Status Tugas <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" selected disabled>Pilih status</option>
                                <option value="in_progress">Dalam Proses</option>
                                <option value="completed">Selesai</option>
                                <option value="on_hold">Ditunda</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success w-100 py-2">
                            <i class="bi bi-check-circle-fill me-2"></i> Tambah Tugas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
