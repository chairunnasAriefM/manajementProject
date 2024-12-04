@extends('layouts.main')

@section('title', 'Manage Projects')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Project Management</h4>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive p-3">
                            <!-- Form Pencarian -->
                            <form action="{{ route('showProjects') }}" method="GET" class="mb-3">
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
                                                <td>{{ $project->description }}</td>
                                                <td>{{ $project->start_date }}</td>
                                                <td>{{ $project->end_date }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $project->status == 'completed' ? 'bg-success' : ($project->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <!-- Show Modal -->
                                                        <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                            data-bs-target="#projectModal{{ $project->id }}">
                                                            <i class="bi bi-eye"></i>
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
                                                        <div class="modal-body">
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


