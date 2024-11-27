@extends('layouts.main')

@section('title', 'Tambah User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tambah User</h4>
        </div>

        <div class="card-body">
            {{-- Menampilkan pesan sukses --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Menampilkan pesan error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('addnewuser.post') }}" method="POST">
                @csrf

                <div class="form-group position-relative mb-3">
                    <label for="name">Nama</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name" class="form-control"
                               placeholder="name" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="form-group position-relative mb-3">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control"
                               placeholder="Email" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group position-relative mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Password">
                    </div>
                </div>

                <div class="form-group position-relative mb-3">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" placeholder="Konfirmasi Password">
                    </div>
                </div>

                <div class="form-group position-relative mb-3">
                    <label for="role">Role</label>
                    <select name="role" id="role" class="form-control">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Tambah User</button>
            </form>
        </div>
    </div>
@endsection
