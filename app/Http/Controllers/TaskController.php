<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'status' => 'required',
        ]);

        $task = new Task();
        $task->fill($requestData);
        $task->save();

        // Notif
        Notification::create([
            'user_id' => $requestData['assigned_to'],
            'message' => "Anda telah ditugaskan ke tugas \"{$task->title}\".",
        ]);



        return back()->with('success', 'Task berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::with(['assignee', 'project'])->findOrFail($id);
        $members = $task->project ? $task->project->members : [];

        return view('common.Tasks.detail', compact('task', 'members'));
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
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validatedData);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->project->created_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus tugas ini.',
            ]);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.',
        ]);
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

    public function markCompleted(Task $task)
    {
        if ($task->project->created_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menyelesaikan tugas ini.',
            ]);
        }

        $task->status = 'completed';
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditandai sebagai selesai.',
        ]);
    }

    public function addTime(Request $request, Task $task)
    {
        if ($task->project->created_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk memperbarui due date tugas ini.',
            ]);
        }

        // Validasi input
        $request->validate([
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        // Perbarui due date
        $task->due_date = $request->input('due_date');
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Due date tugas berhasil diperbarui.',
            'due_date' => $task->due_date,
        ]);
    }

    public function markWorking(Task $task)
    {
        if ($task->assigned_to !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menandai tugas ini sedang dikerjakan.',
            ]);
        }

        $task->status = 'in_progress';
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditandai sebagai sedang dikerjakan.',
        ]);
    }
}
