@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-4">Semua Notifikasi</h4>
        <ul class="list-group">
            @forelse ($notifications as $notification)
                <li class="list-group-item">
                    <div>
                        <strong>{{ $notification->message }}</strong>
                        <span class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-center text-muted">Tidak ada notifikasi.</li>
            @endforelse
        </ul>
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
