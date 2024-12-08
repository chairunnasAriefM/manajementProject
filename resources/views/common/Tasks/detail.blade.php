@extends('layouts.main')

@section('content')
<div class="container">
    <h3>{{ $task->title }}</h3>
    <p><strong>Deskripsi:</strong> {{ $task->description }}</p>
    <p><strong>Status:</strong> {{ $task->status }}</p>
    <p><strong>Proyek:</strong> <a href="{{ route('projects.details', $task->project->id) }}">{{ $task->project->title }}</a></p>
    <p><strong>Assigned to:</strong></p>
    <ul>
        @foreach ($task->assignees as $assignee)
            <li>{{ $assignee->name }} ({{ $assignee->email }})</li>
        @endforeach
    </ul>
</div>
@endsection
