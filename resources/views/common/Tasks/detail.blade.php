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
                        <p class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</p>
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
                            <button class="btn btn-success me-2" id="mark-completed-button">✔ Tandai sebagai
                                Selesai</button>
                            <button class="btn btn-primary me-2" id="add-time-button">⏱ Tambahkan Waktu</button>
                            <button class="btn btn-danger" id="delete-task-button">🗑 Hapus Tugas</button>
                        </div>
                    @elseif(auth()->user()->id === $task->assigned_to)
                        @if ($task->status === 'completed')
                            <div class="alert alert-secondary" role="alert">
                                Tugas ini sudah anda selesaikan
                            </div>
                        @elseif ($task->status === 'pending')
                            <button class="btn btn-primary" id="mark-working-button"><i class="bi bi-arrow-clockwise"></i>
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
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <input type="hidden" name="content" id="content">
                        <div class="mb-3">
                            <div id="summernote"></div>
                        </div>
                        <button type="submit" class="btn btn-info text-white shadow"><i class="bi bi-chat-dots-fill"></i>
                            Tambahkan Komentar</button>
                    </form>

                    <!-- Display Comments -->
                    <div class="mt-4">
                        @if ($task->comments && $task->comments->count())
                            @foreach ($task->comments->sortByDesc('created_at') as $comment)
                                <div class="card p-3 mt-2 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="user d-flex flex-row align-items-center">
                                            <img src="{{ $comment->user->avatar ?? 'https://i.imgur.com/hczKIze.jpg' }}"
                                                width="40" class="user-img rounded-circle me-2">
                                            <span>
                                                <small
                                                    class="font-weight-bold text-primary">{{ $comment->user->name }}</small>
                                                <p class="mb-0">{!! $comment->content !!}</p>
                                                {{-- {{ $comment->user->user_id }}
                                                {{ $comment-user }} --}}
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



                                    {{-- {{ $task->comments-> }}     --}}





                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Tidak ada komentar.</p>
                        @endif
                    </div>

                    <!-- Modal Edit Komentar -->
                    <div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCommentModalLabel">Edit Komentar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editCommentForm" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="commentContent" class="form-label">Isi Komentar</label>
                                            <textarea id="commentContent" name="content" class="form-control" rows="4" required></textarea>
                                        </div>
                                        <input type="hidden" id="commentId" name="commentId">
                                        <button type="submit" class="btn btn-primary">Perbarui Komentar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        const editCommentModal = document.getElementById('editCommentModal');
                        editCommentModal.addEventListener('show.bs.modal', event => {
                            const button = event.relatedTarget; // Tombol yang memicu modal
                            const commentId = button.getAttribute('data-id');
                            const commentContent = button.getAttribute('data-content');

                            // Mengisi data ke dalam modal
                            const modalBody = editCommentModal.querySelector('.modal-body #commentContent');
                            const modalId = editCommentModal.querySelector('.modal-body #commentId');

                            modalBody.value = commentContent;
                            modalId.value = commentId;

                            // Mengubah action form untuk mengupdate komentar
                            const form = document.getElementById('editCommentForm');
                            form.action = `/comments/${commentId}`; // Sesuaikan dengan route update Anda
                        });
                    </script>
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
    </script>
@endsection
