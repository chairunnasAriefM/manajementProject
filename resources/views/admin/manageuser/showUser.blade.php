@extends('layouts.main')

@section('title', 'Manage Users')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">User Management</h4>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive p-3">
                            <!-- Form Pencarian -->
                            <form action="{{ route('showuser') }}" method="GET" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pengguna..."
                                        value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </div>
                            </form>

                            <table class="table table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>ROLE</th>
                                        <th>CREATED AT</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center">Data Tidak Ditemukan</td>
                                        </tr>
                                    @else
                                        @foreach ($data as $user)
                                            <tr id="user-{{ $user->id }}">
                                                <td class="text-bold-500">{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $user->role == 'admin' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <div class="d-flex">

                                                        <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                            data-bs-target="#showUserModal{{ $user->id }}">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <!-- Modal Show User -->
                                                        <div class="modal fade" id="showUserModal{{ $user->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="showUserModalLabel{{ $user->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content border-0 shadow-lg">
                                                                    <div class="modal-header bg-primary text-white">
                                                                        <h5 class="modal-title text-white"
                                                                            id="showUserModalLabel{{ $user->id }}">
                                                                            <i class="bi bi-info-circle"></i> User Details:
                                                                            {{ $user->name }}
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <!-- Avatar User -->
                                                                            @if ($user->avatar_path)
                                                                                <div class="col-md-4 text-center">
                                                                                    <img src="{{ asset($user->avatar_path) }}"
                                                                                        alt="Avatar"
                                                                                        class="img-fluid rounded-circle shadow">
                                                                                </div>
                                                                            @endif
                                                                            <!-- Informasi User -->
                                                                            <div
                                                                                class="col-md-{{ $user->avatar_path ? '8' : '12' }}">
                                                                                <h6><i
                                                                                        class="bi bi-person-fill text-primary me-2"></i><strong>Name:</strong>
                                                                                </h6>
                                                                                <p>{{ $user->name }}</p>
                                                                                <h6><i
                                                                                        class="bi bi-envelope-fill text-primary me-2"></i><strong>Email:</strong>
                                                                                </h6>
                                                                                <p>{{ $user->email }}</p>
                                                                                <h6><i
                                                                                        class="bi bi-people-fill text-primary me-2"></i><strong>Role:</strong>
                                                                                </h6>
                                                                                <p>{{ ucfirst($user->role) }}</p>
                                                                                <h6><i
                                                                                        class="bi bi-calendar-check-fill text-primary me-2"></i><strong>Created
                                                                                        At:</strong></h6>
                                                                                <p>{{ $user->created_at }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal"
                                                            data-bs-target="#editUserModal{{ $user->id }}"
                                                            data-bs-placement="top" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>

                                                        <!-- Modal untuk Edit User -->
                                                        <div class="modal fade" id="editUserModal{{ $user->id }}"
                                                            tabindex="-1" aria-labelledby="editUserModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form id="editUserForm{{ $user->id }}"
                                                                        onsubmit="updateUser(event, {{ $user->id }})"
                                                                        enctype="multipart/form-data"
                                                                        action="{{ route('updateuser', ['id' => $user->id]) }}"
                                                                        method="POST">
                                                                        @method('put')
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="editUserModalLabel">
                                                                                Edit User: {{ $user->name }}</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    for="name-{{ $user->id }}">Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="name-{{ $user->id }}"
                                                                                    name="name"
                                                                                    value="{{ $user->name }}" required>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    for="email-{{ $user->id }}">Email</label>
                                                                                <input type="email" class="form-control"
                                                                                    id="email-{{ $user->id }}"
                                                                                    name="email"
                                                                                    value="{{ $user->email }}" required>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    for="role-{{ $user->id }}">Role</label>
                                                                                <select class="form-select"
                                                                                    id="role-{{ $user->id }}"
                                                                                    name="role" required>
                                                                                    <option value="admin"
                                                                                        {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                                                        Admin</option>
                                                                                    <option value="member"
                                                                                        {{ $user->role == 'member' ? 'selected' : '' }}>
                                                                                        Member</option>
                                                                                    <option value="leader"
                                                                                        {{ $user->role == 'leader' ? 'selected' : '' }}>
                                                                                        Leader</option>
                                                                                    <option value="programmer"
                                                                                        {{ $user->role == 'programmer' ? 'selected' : '' }}>
                                                                                        Programmer</option>
                                                                                    <option value="3dArtist"
                                                                                        {{ $user->role == '3dArtist' ? 'selected' : '' }}>
                                                                                        3D Artist</option>
                                                                                    <option value="2dArtist"
                                                                                        {{ $user->role == '2dArtist' ? 'selected' : '' }}>
                                                                                        2D Artist</option>
                                                                                    <option value="music composer"
                                                                                        {{ $user->role == 'music composer' ? 'selected' : '' }}>
                                                                                        Music Composer</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    for="avatar-{{ $user->id }}">Avatar</label>
                                                                                <input type="file" class="form-control"
                                                                                    id="avatar-{{ $user->id }}"
                                                                                    name="avatar" accept="image/*">
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    for="password-{{ $user->id }}">Password</label>
                                                                                <input type="password"
                                                                                    class="form-control"
                                                                                    id="password-{{ $user->id }}"
                                                                                    name="password"
                                                                                    placeholder="Biarkan kosong jika tidak ingin dirubah">
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Save
                                                                                Changes</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>


                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="deleteUser({{ $user->id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Hapus">
                                                            <i class="bi bi-trash text-black"></i>
                                                        </button>

                                                        <form id="delete-form-{{ $user->id }}"
                                                            action="{{ route('user.delete', $user->id) }}" method="POST"
                                                            style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif



                                </tbody>
                            </table>

                            <!-- Paginasi -->
                            <div class="pagination-container">
                                {{ $data->links('vendor.pagination.custom-pagination') }}
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
        function deleteUser(userId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${userId}`).submit();
                }
            });
        }
    </script>


@endsection
