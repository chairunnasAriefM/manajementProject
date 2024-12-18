@extends('layouts.main')

@section('content')
    <div class="container py-5">
        <!-- Task Detail Card -->
        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <!-- Header -->

            <div class="card-header bg-primary text-light py-4 position-relative">
                <h3 class="mb-0 text-white">{{ $task->title }}</h3>
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
                <!-- Task Information -->
                <div class="row mt-4">
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
                        <p class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-primary fw-bold">Ditugaskan Kepada</h5>
                        <div class="d-flex align-items-center">
                            <span
                                class="fw-semibold">{{ $task->assignee ? $task->assignee->name : 'Belum ditugaskan' }}</span>
                            <span
                                class="badge bg-info text-dark ms-3">{{ $task->assignee ? $task->assignee->email : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-4">
                    @if (auth()->user()->id === $task->project->created_by)
                        <div>
                            <button class="btn btn-success me-2" id="mark-completed-button"><i
                                    class="bi bi-check2-square"></i> Tandai sebagai
                                Selesai</button>
                            <button class="btn btn-primary me-2" id="add-time-button"><i class="bi bi-alarm"></i> Tambahkan
                                Waktu</button>
                            <button class="btn btn-warning me-2 " id="update-task-button" data-bs-toggle="modal"
                                data-bs-target="#editTaskModal"><i class="bi bi-pencil-square text-white"></i>
                                <div class="text-white" style="display: inline-block;">Edit Tugas</div>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg ">
                                    <div class="modal-content">
                                        <form id="editTaskForm" method="POST"
                                            action="{{ route('tasks.update', $task->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-primary text-white rounded-top">
                                                <i class="bi bi-plus-circle-fill me-2 mb-2"></i>
                                                <h5 class="modal-title text-white">
                                                    Edit Tugas
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body bg-body-secondary text-body">
                                                <!-- Title -->
                                                <div class="mb-3">
                                                    <label for="taskTitle" class="form-label">Judul Tugas</label>
                                                    <input type="text" name="title" class="form-control" id="taskTitle"
                                                        value="{{ old('title', $task->title) }}" required>
                                                </div>

                                                <!-- Description -->
                                                <div class="mb-3">
                                                    <label for="taskDescription" class="form-label">Deskripsi</label>
                                                    <textarea name="description" class="form-control" id="taskDescription" rows="3" required>{{ old('description', $task->description) }}</textarea>
                                                </div>

                                                <!-- Assigned To -->
                                                <div class="mb-3">
                                                    <label for="assignedTo" class="form-label">Ditugaskan Kepada</label>
                                                    <select name="assigned_to" id="assignedTo" class="choices form-select"
                                                        required>
                                                        @foreach ($members as $member)
                                                            <option value="{{ $member->id }}"
                                                                {{ $member->id == $task->assigned_to ? 'selected' : '' }}>
                                                                {{ $member->name }}
                                                            </option>
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
                                                        <input type="text" class="form-control datepicker" id="due_date"
                                                            name="due_date" placeholder="Pilih tanggal batas akhir" required
                                                            value="{{ old('due_date', $task->due_date) }}">
                                                    </div>
                                                </div>

                                                <!-- Status -->
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-select" required>
                                                        <option value="pending"
                                                            {{ $task->status == 'pending' ? 'selected' : '' }}>Pending
                                                        </option>
                                                        <option value="in_progress"
                                                            {{ $task->status == 'in_progress' ? 'selected' : '' }}>In
                                                            Progress</option>
                                                        <option value="completed"
                                                            {{ $task->status == 'completed' ? 'selected' : '' }}>Completed
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-danger" id="delete-task-button"><i class="bi bi-trash"></i> Hapus
                                Tugas</button>
                        </div>
                    @elseif(auth()->user()->id === $task->assigned_to)
                        @if ($task->status === 'completed')
                            <div class="alert alert-secondary" role="alert">
                                Tugas ini sudah anda selesaikan
                            </div>
                        @elseif ($task->status === 'pending')
                            <button class="btn btn-primary" id="mark-working-button"><i
                                    class="bi bi-arrow-clockwise"></i>
                                Tandai Sedang Dikerjakan</button>
                            <script>
                                document.addEventListener('click', function(event) {
                                    if (event.target && event.target.id === 'mark-working-button') {
                                        Swal.fire({
                                            title: 'Tandai Sedang Dikerjakan',
                                            text: 'Apakah Anda yakin ingin menandai tugas ini sedang dikerjakan?',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonText: 'Ya, Tandai!',
                                            cancelButtonText: 'Batal',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                fetch("{{ route('tasks.markWorking', $task->id) }}", {
                                                        method: "POST",
                                                        headers: {
                                                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                            "Content-Type": "application/json"
                                                        },
                                                        body: JSON.stringify({})
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        Swal.fire({
                                                            title: data.success ? 'Berhasil!' : 'Gagal!',
                                                            text: data.message,
                                                            icon: data.success ? 'success' : 'error'
                                                        }).then(() => {
                                                            if (data.success) location.reload();
                                                        });
                                                    });
                                            }
                                        });
                                    }
                                });
                            </script>
                        @else
                            <div class="alert alert-secondary" role="alert">
                                Segera selesaikan tugas ini!
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary" role="alert">
                            Anda tidak memiliki akses khusus untuk tugas ini.
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Comments Section -->
        @if (auth()->user()->id === $task->project->created_by || auth()->user()->id === $task->assigned_to)
            <div class="mt-5">
                <h4 class="text-uppercase text-primary fw-bold">Komentar</h4>
                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <form action="{{ route('comments.store') }}" method="POST" onsubmit="return validateCommentForm()">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <input type="hidden" name="content" id="content">
                        <div class="mb-3">
                            <div id="summernote"></div>
                        </div>
                        <button type="submit" class="btn btn-info text-white shadow">
                            <i class="bi bi-chat-dots-fill"></i> Tambahkan Komentar
                        </button>
                    </form>


                    <!-- Display Comments -->
                    <div class="mt-4">
                        @if ($task->comments && $task->comments->count())
                            @foreach ($task->comments->sortByDesc('created_at') as $comment)
                                <div class="card p-3 mt-2 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="user d-flex flex-row align-items-center">
                                            <!-- Perbaikan Avatar -->
                                            <img src="{{ asset('storage/' . $comment->user->avatar) }}" width="40"
                                                height="40" class="user-img rounded-circle me-2 object-fit-cover">
                                            <span>
                                                <small
                                                    class="font-weight-bold text-primary">{{ $comment->user->name }}</small>
                                                <p class="mb-0">{!! $comment->content !!}</p>
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>

                                    @if ($comment->user_id == Auth::user()->id)
                                        <div class="d-flex justify-content-end mt-2">
                                            <a href="#" class="text-info me-3" data-bs-toggle="modal"
                                                data-bs-target="#editCommentModal" data-id="{{ $comment->id }}"
                                                data-content="{{ $comment->content }}">Edit</a>
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                                class="mb-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn text-danger p-0"
                                                    style="none">Hapus</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                                 <!-- Modal Edit Komentar -->
                    <div class="modal modal-xl fade" id="editCommentModal" tabindex="-1"
                    aria-labelledby="editCommentModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCommentModalLabel">Edit Komentar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editCommentForm" method="POST"
                                    action="{{ route('comments.update', $comment->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="editSummernote" class="form-label">Isi Komentar</label>
                                        <!-- Summernote Textarea -->
                                        <textarea id="editSummernote" name="content" class="form-control"></textarea>
                                    </div>
                                    <input type="hidden" id="commentId" name="commentId">
                                    <button type="submit" class="btn btn-primary">Perbarui Komentar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Tidak ada komentar.</p>
                        @endif
                    </div>






                </div>
            </div>
        @endif

    </div>


@endsection

@section('scripts')
    <script src="{{ asset('mazer/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('mazer/extensions/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote
            $('#summernote').summernote({
                height: 150,
                placeholder: 'Tulis komentar Anda...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['fontsize', 'color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });

            // Saat form submit, pindahkan konten Summernote ke input tersembunyi
            $('form').on('submit', function(e) {
                const content = $('#summernote').summernote('code'); // Ambil isi dari Summernote
                $('#content').val(content); // Set value input hidden
            });
        });

        function validateCommentForm() {
            const content = $('#content').val().trim();
            if (!content) {
                alert('Komentar tidak boleh kosong.');
                return false;
            }
            return true;
        }
    </script>
    <style>
        .comment-box {
            color: #333;
            background-color: var(--bs-light, #f8f9fa);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .comment-box:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        // Konfirmasi SweetAlert2 untuk "Tandai sebagai Selesai"
        document.getElementById('mark-completed-button').addEventListener('click', function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menandai tugas ini sebagai selesai?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tandai Selesai!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Logika permintaan ke server untuk menandai selesai
                    fetch("{{ route('tasks.markCompleted', $task->id) }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            }
                        }).then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: data.success ? 'Berhasil!' : 'Gagal!',
                                text: data.message,
                                icon: data.success ? 'success' : 'error'
                            }).then(() => {
                                if (data.success) location.reload();
                            });
                        });
                }
            });
        });

        // Konfirmasi SweetAlert2 untuk "Tambahkan Waktu"
        document.getElementById('add-time-button').addEventListener('click', function() {
            Swal.fire({
                title: 'Tambahkan Waktu',
                html: `
            <label for="due-date-input" class="form-label">Pilih Tanggal Baru:</label>
            <input type="date" id="due-date-input" class="form-control">
        `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tambahkan!',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const dueDate = document.getElementById('due-date-input').value;
                    if (!dueDate) {
                        Swal.showValidationMessage('Tanggal harus diisi!');
                    }
                    return dueDate;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Logika untuk mengirim tanggal baru ke server
                    fetch("{{ route('tasks.addTime', $task->id) }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                due_date: result.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: data.success ? 'Berhasil!' : 'Gagal!',
                                text: data.message,
                                icon: data.success ? 'success' : 'error'
                            }).then(() => {
                                if (data.success) location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memperbarui tanggal.',
                                icon: 'error'
                            });
                        });
                }
            });
        });


        // Konfirmasi SweetAlert2 untuk "Hapus Tugas"
        document.getElementById('delete-task-button').addEventListener('click', function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus tugas ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Logika permintaan ke server untuk menghapus tugas
                    fetch("{{ route('tasks.delete', $task->id) }}", {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            }
                        }).then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: data.success ? 'Berhasil!' : 'Gagal!',
                                text: data.message,
                                icon: data.success ? 'success' : 'error'
                            }).then(() => {
                                if (data.success) location.reload();
                            });
                        });
                }
            });
        });

        // flatpicker
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd F Y',
                allowInput: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote untuk modal edit
            $('#editSummernote').summernote({
                height: 200,
                tabsize: 2,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ]
            });

            // Buka modal dan isi Summernote dengan konten yang ada
            $('#editCommentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var commentId = button.data('id');
                var commentContent = button.data('content');

                var actionUrl = "{{ route('comments.update', ':id') }}".replace(':id', commentId);
                $('#editCommentForm').attr('action', actionUrl);

                // Set konten Summernote
                $('#editSummernote').summernote('code', commentContent);
            });

        });
    </script>
@endsection
