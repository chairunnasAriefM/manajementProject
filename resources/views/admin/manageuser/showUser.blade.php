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
                                                            data-bs-target="#userModal{{ $user->id }}"
                                                            data-bs-placement="top" title="Show">
                                                            <i class="bi bi-eye "></i>
                                                        </button>

                                                        <a href="{{ route('admin.edit', $user->id) }}"
                                                            class="btn btn-sm btn-warning me-2" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="deleteUser({{ $user->id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                            <i class="bi bi-trash text-black"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1"
                                                aria-labelledby="userModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="userModalLabel">User Details:
                                                                {{ $user->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul>
                                                                <li><strong>Name:</strong> {{ $user->name }}</li>
                                                                <li><strong>Email:</strong> {{ $user->email }}</li>
                                                                <li><strong>Role:</strong> {{ ucfirst($user->role) }}</li>
                                                                <li><strong>Created At:</strong>
                                                                    {{ $user->created_at->format('d M Y H:i') }}</li>
                                                            </ul>
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
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/deleteuser/' + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', // CSRF token
                        },
                        success: function(response) {
                            if (response.success) {
                                // Show success toast
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                // Remove the user row from the table without reload
                                $('#user-' + userId).remove(); // Remove the specific row by ID
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'There was an issue with the deletion.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
