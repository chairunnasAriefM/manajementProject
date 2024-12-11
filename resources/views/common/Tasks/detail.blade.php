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
                                class="text-dark fw-semibold">{{ $task->assignee ? $task->assignee->name : 'Belum ditugaskan' }}</span>
                            <span
                                class="badge bg-info text-dark ms-3">{{ $task->assignee ? $task->assignee->email : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-4">
                    @if (auth()->user()->id === $task->project->created_by)
                        <div>
                            <button class="btn btn-success me-2" onclick="completeTask()">✔ Tandai sebagai Selesai</button>
                            <button class="btn btn-primary me-2" onclick="addTime()">⏱ Tambahkan Waktu</button>
                            <button class="btn btn-danger" onclick="deleteTask()">🗑 Hapus Tugas</button>
                        </div>
                    @elseif(auth()->user()->id === $task->assigned_to)
                        <button class="btn btn-primary" onclick="markProgress()">🔄 Tandai Sedang Dikerjakan</button>
                    @else
                        <div class="alert alert-secondary" role="alert">
                            Anda tidak memiliki akses khusus untuk tugas ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Comments Section -->
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
                    <button type="submit" class="btn btn-info text-white shadow">💬 Tambahkan Komentar</button>
                </form>

                <!-- Display Comments -->
                <div class="mt-4">
                    @if ($task->comments && $task->comments->count())
                        @foreach ($task->comments->sortByDesc('created_at') as $comment)
                            <div class="card p-3 mt-2 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="user d-flex flex-row align-items-center">
                                        <img src="{{ $comment->user->profile_picture_url ?? 'https://i.imgur.com/hczKIze.jpg' }}"
                                            width="40" class="user-img rounded-circle me-2">
                                        <span>
                                            <small class="font-weight-bold text-primary">{{ $comment->user->name }}</small>
                                            <p class="mb-0">{!! $comment->content !!}</p>
                                        </span>
                                    </div>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <a href="#" class="text-info me-3" data-bs-toggle="modal"
                                        data-bs-target="#editCommentModal" data-id="{{ $comment->id }}"
                                        data-content="{!! $comment->content !!}">Edit</a>
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                        class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0">Hapus</button>
                                    </form>
                                </div>
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
@endsection
