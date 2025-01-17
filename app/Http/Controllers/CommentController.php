<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'task_id' => $validated['task_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);


        //  notifikasi untuk pemilik tugas
        $task = $comment->task;

        // Untuk member
        if ($task->assigned_to !== auth()->id()) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'message' => "Komentar baru pada tugas \"{$task->title}\".",
                'type' => 'tasks',
                'reference_id' => $task->id
            ]);
        }

        // Untuk leader
        if ($task->project->created_by !== auth()->id()) {
            Notification::create([
                'user_id' => $task->project->created_by,
                'message' => "Komentar baru pada tugas \"{$task->title}\".",
                'type' => 'tasks',
                'reference_id' => $task->id
            ]);
        }



        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->content = $request->input('content');
        $comment->save();

        return redirect()->back()->with('success', 'Komentar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }
}
