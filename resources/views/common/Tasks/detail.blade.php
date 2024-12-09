@extends('layouts.main')

@section('content')
    <div class="container py-5">
        <!-- Card Container -->
        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <!-- Header -->
            <div class="card-header bg-gradient text-white py-4 position-relative"
                style="background: linear-gradient(90deg, #4e73df, #1cc88a);">
                <h3 class="mb-0">{{ $task->title }}</h3>
                <span
                    class="position-absolute top-50 end-0 translate-middle-y me-4 badge rounded-pill
                @if ($task->status === 'completed') bg-success
                @elseif($task->status === 'in_progress') bg-warning text-dark
                @else bg-secondary @endif">
                    {{ ucfirst($task->status) }}
                </span>
            </div>

            <!-- Body -->
            <div class="card-body">
                <!-- Detail Informasi -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-primary fw-bold">Deskripsi</h5>
                        <p class="text-muted">{{ $task->description }}</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-primary fw-bold">Proyek</h5>
                        <p>
                            <a href="{{ route('projects.show', $task->project->id) }}"
                                class="text-decoration-none text-info fw-semibold">
                                {{ $task->project->title }}
                            </a>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-primary fw-bold">Tanggal Batas Waktu</h5>
                        <p class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-primary fw-bold">Ditugaskan Kepada</h5>
                        <div class="d-flex align-items-center">
                            <span
                                class="text-dark fw-semibold">{{ $task->assignee ? $task->assignee->name : 'Belum ditugaskan' }}</span>
                            <span
                                class="badge bg-info text-dark ms-3">{{ $task->assignee ? $task->assignee->email : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Aksi -->
                <div class="mt-4">
                    @if (auth()->user()->id === $task->project->created_by)
                        <!-- Tampilan untuk Leader -->
                        <h5 class="fw-bold">Aksi untuk Leader</h5>
                        <div>
                            <button class="btn btn-success me-2" onclick="completeTask()">✔ Tandai sebagai Selesai</button>
                            <button class="btn btn-danger" onclick="deleteTask()">🗑 Hapus Tugas</button>
                        </div>
                    @elseif(auth()->user()->id === $task->assigned_to)
                        <!-- Tampilan untuk Assigned User -->
                        <h5 class="fw-bold">Aksi untuk Assigned User</h5>
                        <button class="btn btn-primary" onclick="markProgress()">🔄 Tandai Sedang Dikerjakan</button>
                    @else
                        <div class="alert alert-secondary" role="alert">
                            Anda tidak memiliki akses khusus untuk tugas ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Script -->
    <script>
        function completeTask() {
            alert('Task berhasil ditandai sebagai selesai!');
        }

        function deleteTask() {
            if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
                alert('Task berhasil dihapus!');
            }
        }

        function markProgress() {
            alert('Task sedang ditandai sebagai sedang dikerjakan!');
        }
    </script>
@endsection
