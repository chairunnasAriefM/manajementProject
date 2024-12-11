<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function taskDetails($id)
    {
        $task = Task::with(['assignees', 'project'])->findOrFail($id);

        return view('tasks.details', compact('task'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'project_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date',
            // 'status' => '',
        ]);

        $task = new Task();
        $task->fill($requestData);
        $task->save();

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::with(['assignee', 'project'])->findOrFail($id);

        return view('common.Tasks.detail', compact('task'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'required|date',
        ]);

        $task->update([
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        return back()->with('success', 'Tugas berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    public function addComment(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'content' => $validatedData['content'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
