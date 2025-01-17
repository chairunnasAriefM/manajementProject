<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Pagination


        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead()
    {
        Notification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function checkDueDate()
{
    $today = now()->toDateString();

    // Notifikasi untuk tasks
    $tasksEndingToday = Task::whereDate('due_date', now()->toDateString())->whereNotIn('status', ['completed'])->get();
    foreach ($tasksEndingToday as $task) {
        Notification::updateOrCreate(
            [
                'user_id' => $task->assigned_to,
                'type' => 'tasks',
                'reference_id' => $task->id,
            ],
            [
                'message' => "Tugas \"{$task->title}\" berakhir hari ini.",
                'is_read' => false,
            ]
        );
    }

    // Notifikasi projects
    $projectsEndingToday = Project::whereDate('end_date', $today)->whereNotIn('status', ['completed'])->get();
    foreach ($projectsEndingToday as $project) {
        Notification::updateOrCreate(
            [
                'user_id' => $project->created_by,
                'type' => 'projects',
                'reference_id' => $project->id,
            ],
            [
                'message' => "Proyek \"{$project->title}\" berakhir hari ini.",
                'is_read' => false,
            ]
        );
    }
}

}
