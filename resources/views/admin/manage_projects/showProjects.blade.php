@extends('layouts.main')

@section('title', 'Manage Projects')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Daftar Proyek</h4>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive p-3">
                            <!-- Form Pencarian -->
                            <form action="{{ route('searchProjectsAdmin') }}" method="GET" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari proyek..."
                                        value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </div>
                            </form>

                            <table class="table table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>JUDUL</th>
                                        <th>DESKRIPSI</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($projects->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center">Data Tidak Ditemukan</td>
                                        </tr>
                                    @else
                                        @foreach ($projects as $project)
                                            <tr id="project-{{ $project->id }}">
                                                <td class="text-bold-500">{{ $project->title }}</td>
                                                <td>{{ Str::limit($project->description, 50) }} </td>
                                                <td>{{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('d F Y') }}
                                                </td>

                                                <td>
                                                    <span
                                                        class="badge {{ $project->status == 'completed' ? 'bg-success' : ($project->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <!-- Show Modal -->
                                                        {{-- <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                            data-bs-target="#projectModal{{ $project->id }}">
                                                            <i class="bi bi-eye"></i>
                                                        </button> --}}

                                                        <a href="{{ route('projects.show', $project->id) }}"
                                                            class="btn btn-sm btn-info me-2">
                                                            <i class="bi bi-eye"></i>
                                                        </a>


                                                        <!-- Edit Modal -->
                                                        <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal"
                                                            data-bs-target="#editProjectModal{{ $project->id }}">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>

                                                        <!-- Edit Project Modal -->
                                                        <div class="modal fade" id="editProjectModal{{ $project->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="editProjectLabel{{ $project->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <form id="editProjectForm{{ $project->id }}"
                                                                        onsubmit="updateProject(event, {{ $project->id }})">
                                                                        <div class="modal-header bg-primary text-white">
                                                                            <h5 class="modal-title text-white"
                                                                                id="editProjectLabel{{ $project->id }}">
                                                                                <i class="bi bi-pencil-square"></i> Edit
                                                                                Proyek: {{ $project->title }}
                                                                            </h5>
                                                                            <button type="button"
                                                                                class="btn-close btn-close-white"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body bg-body-secondary text-body">
                                                                            <div class="row">
                                                                                <!-- Title Input -->
                                                                                <div class="col-md-12 mb-3">
                                                                                    <label for="title-{{ $project->id }}"
                                                                                        class="form-label fw-bold">Judul
                                                                                        Proyek</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="title-{{ $project->id }}"
                                                                                        name="title"
                                                                                        value="{{ $project->title }}"
                                                                                        required>
                                                                                </div>

                                                                                <!-- Description Input -->
                                                                                <div class="col-md-12 mb-3">
                                                                                    <label
                                                                                        for="description-{{ $project->id }}"
                                                                                        class="form-label fw-bold">Deskripsi</label>
                                                                                    <textarea class="form-control" id="description-{{ $project->id }}" name="description" rows="4"
                                                                                        placeholder="Tambahkan deskripsi proyek">{{ $project->description }}</textarea>
                                                                                </div>

                                                                                <!-- Tanggal Mulai -->
                                                                                <div class="col-md-6 mb-3">
                                                                                    <label
                                                                                        for="start_date-{{ $project->id }}"
                                                                                        class="form-label fw-bold">Tanggal
                                                                                        Mulai</label>
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-text bg-primary text-white d-flex align-items-center justify-content-center">
                                                                                            <i
                                                                                                class="bi bi-calendar-date mb-2"></i>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            class="form-control datepicker"
                                                                                            id="start_date-{{ $project->id }}"
                                                                                            name="start_date"
                                                                                            value="{{ $project->start_date }}"
                                                                                            placeholder="Pilih tanggal mulai"
                                                                                            required>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Tanggal Selesai -->
                                                                                <div class="col-md-6 mb-3">
                                                                                    <label
                                                                                        for="end_date-{{ $project->id }}"
                                                                                        class="form-label fw-bold">Tanggal
                                                                                        Selesai</label>
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-text bg-primary text-white d-flex align-items-center justify-content-center">
                                                                                            <i
                                                                                                class="bi bi-calendar-date mb-2"></i>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            class="form-control datepicker"
                                                                                            id="end_date-{{ $project->id }}"
                                                                                            name="end_date"
                                                                                            value="{{ $project->end_date }}"
                                                                                            placeholder="Pilih tanggal selesai"
                                                                                            required>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Status Dropdown -->
                                                                                <div class="col-md-12 mb-3">
                                                                                    <label for="status-{{ $project->id }}"
                                                                                        class="form-label fw-bold">Status</label>
                                                                                    <select class="form-select"
                                                                                        id="status-{{ $project->id }}"
                                                                                        name="status" required>
                                                                                        <option value="in_progress"
                                                                                            {{ $project->status == 'in_progress' ? 'selected' : '' }}>
                                                                                            In Progress</option>
                                                                                        <option value="completed"
                                                                                            {{ $project->status == 'completed' ? 'selected' : '' }}>
                                                                                            Completed</option>
                                                                                        <option value="on_hold"
                                                                                            {{ $project->status == 'on_hold' ? 'selected' : '' }}>
                                                                                            On Hold</option>
                                                                                    </select>
                                                                                </div>

                                                                                <!-- Project Members -->
                                                                                <div class="col-md-12 mb-3">
                                                                                    <label
                                                                                        for="members-{{ $project->id }}"
                                                                                        class="form-label fw-bold">Anggota
                                                                                        Proyek</label>
                                                                                    <select class="choices form-select"
                                                                                        id="members-{{ $project->id }}"
                                                                                        name="members[]" multiple required>
                                                                                        <optgroup label="Pilih Anggota">
                                                                                            @foreach ($users as $user)
                                                                                                @if (!in_array($user->role, ['admin', 'leader']))
                                                                                                    <option
                                                                                                        value="{{ $user->id }}"
                                                                                                        {{ $project->members->contains($user->id) ? 'selected' : '' }}>
                                                                                                        {{ $user->name }}
                                                                                                        ({{ $user->role }})
                                                                                                    </option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </optgroup>
                                                                                    </select>
                                                                                </div>


                                                                                <!-- Daftar Anggota yang Sudah Dipilih -->
                                                                                <div id="selected-members-{{ $project->id }}"
                                                                                    class="mt-3">
                                                                                    <h5 class="mb-3">Anggota Terpilih
                                                                                    </h5>
                                                                                    <ul id="selected-members-list-{{ $project->id }}"
                                                                                        class="list-group">
                                                                                        @foreach ($project->members as $member)
                                                                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                                                                id="member-{{ $member->id }}">
                                                                                                <div>
                                                                                                    <span
                                                                                                        class="fw-bold">{{ $member->name }}</span>
                                                                                                    <span
                                                                                                        class="text-muted">({{ $member->role }})</span>
                                                                                                </div>
                                                                                                <button type="button"
                                                                                                    class="btn btn-danger btn-sm"
                                                                                                    onclick="removeMember({{ $project->id }}, {{ $member->id }})">
                                                                                                    <i
                                                                                                        class="bi bi-x-circle"></i>
                                                                                                    Hapus
                                                                                                </button>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Tutup</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Simpan
                                                                                Perubahan</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="deleteProject({{ $project->id }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="projectModal{{ $project->id }}" tabindex="-1"
                                                aria-labelledby="projectModalLabel{{ $project->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content border-0 shadow-lg">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title text-white"
                                                                id="projectModalLabel{{ $project->id }}">
                                                                <i class="bi bi-info-circle"></i> Project Details:
                                                                {{ $project->title }}
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body bg-body-secondary text-body">
                                                            <div class="row">
                                                                <!-- Informasi Proyek -->
                                                                <div class="col-md-6">
                                                                    <h6><i
                                                                            class="bi bi-bookmark-fill text-primary me-2"></i><strong>Title:</strong>
                                                                    </h6>
                                                                    <p>{{ $project->title }}</p>
                                                                    <h6><i
                                                                            class="bi bi-file-earmark-text-fill text-primary me-2"></i><strong>Description:</strong>
                                                                    </h6>
                                                                    <p>{{ $project->description }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6><i
                                                                            class="bi bi-calendar-event-fill text-primary me-2"></i><strong>Start
                                                                            Date:</strong></h6>
                                                                    <p>{{ $project->start_date }}</p>
                                                                    <h6><i
                                                                            class="bi bi-calendar-check-fill text-primary me-2"></i><strong>End
                                                                            Date:</strong></h6>
                                                                    <p>{{ $project->end_date }}</p>
                                                                    <h6><i
                                                                            class="bi bi-flag-fill text-primary me-2"></i><strong>Status:</strong>
                                                                    </h6>
                                                                    <span
                                                                        class="badge {{ $project->status == 'completed' ? 'bg-success' : ($project->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
                                                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <!-- Daftar Anggota Proyek -->
                                                            <h6><i class="bi bi-people-fill text-primary me-2"></i><strong>Project
                                                                    Members:</strong></h6>
                                                            @if ($project->members->isNotEmpty())
                                                                <ul class="list-group">
                                                                    @foreach ($project->members as $member)
                                                                        <li
                                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <div>
                                                                                <strong>{{ $member->name }}</strong>
                                                                                <small>({{ $member->email }})</small>
                                                                            </div>
                                                                            <span
                                                                                class="badge bg-info text-dark">{{ ucfirst($member->role ?? 'Member') }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <p class="text-muted">No members assigned to this project.
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="pagination-container">
                                {{ $projects->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function deleteProject(projectId) {
            Swal.fire({
                title: 'Kamu yakin?',
                text: 'Kamu tidak akan bisa mengembalikan ini!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/projects/${projectId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $(`#project-${projectId}`).remove();
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to delete project!', 'error');
                        }
                    });
                }
            });
        }

        function updateProject(event, projectId) {
            event.preventDefault();
            const formData = new FormData(document.getElementById(`editProjectForm${projectId}`));
            $.ajax({
                url: `/projects/${projectId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                success: function(response) {
                    Swal.fire('Updated!', response.message, 'success');
                    location.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to update project! ' + xhr.responseJSON.message, 'error');
                }
            });
        }


        function confirmRemoveMember(projectId, memberId) {
            Swal.fire({
                title: 'Yakin ingin menghapus anggota ini?',
                text: "Anggota yang dihapus tidak akan kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeMember(projectId, memberId);
                }
            });
        }

        function removeMember(projectId, memberId) {
            // Menghapus anggota dari tampilan
            const memberItem = document.getElementById(`member-${memberId}`);
            memberItem.remove();

            // Menghapus anggota dari daftar select multiple
            const selectElement = document.getElementById(`members-${projectId}`);
            const optionToRemove = Array.from(selectElement.options).find(option => option.value == memberId);
            if (optionToRemove) {
                optionToRemove.selected = false;
            }

            // Mengirim permintaan AJAX untuk menghapus anggota dari proyek di backend
            fetch(`/project/${projectId}/remove-member`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        memberId: memberId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Berhasil!',
                            'Anggota berhasil dihapus dari proyek.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat menghapus anggota.',
                            'error'
                        );
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

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
