<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // Data umum
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        $activeUsersData = User::whereNotNull('email_verified_at')->get();
        $newNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Data spesifik per role
        $projectsLed = [];
        $teamTasks = 0;
        $teamProgress = 0;

        if ($role === 'leader') {
            // Proyek yang dipimpin oleh leader
            $projectsLed = Project::where('created_by', $user->id)->get();
            $teamTasks = Task::whereIn('project_id', $projectsLed->pluck('id'))->count();
            $teamProgress = Task::whereIn('project_id', $projectsLed->pluck('id'))
                ->where('status', 'completed')
                ->count();
        }

        $myTasks = $role !== 'admin' ? Task::where('assigned_to', $user->id)->count() : 0;
        $myTasksList = $role !== 'admin' ? Task::where('assigned_to', $user->id)->get() : [];

        // Statistik status proyek untuk Admin
        $inProgress = $role === 'admin' ? Project::where('status', 'in_progress')->count() : 0;
        $completed = $role === 'admin' ? Project::where('status', 'completed')->count() : 0;
        $onHold = $role === 'admin' ? Project::where('status', 'on_hold')->count() : 0;

        // Kirim data ke view
        return view('dashboard', [
            'role' => $role,
            'totalProjects' => $totalProjects,
            'totalTasks' => $totalTasks,
            'activeUsers' => $activeUsers,
            'newNotifications' => $newNotifications,
            'projectsLed' => $projectsLed,
            'teamTasks' => $teamTasks,
            'teamProgress' => $teamProgress,
            'myTasks' => $myTasks,
            'myTasksList' => $myTasksList,
            'inProgress' => $inProgress,
            'completed' => $completed,
            'onHold' => $onHold,
            'activeUsersData' => $activeUsersData,
        ]);
    }
}
