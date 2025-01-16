<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Notification;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shetabit\Visitor\Traits\Visitor;

class DashboardController extends Controller
{
    public function index()
    {
        // visitor()->visit();
        $user = Auth::user();
        $role = $user->role;

        // Data umum
        $totalAllProjects = Project::count();
        $totalTasks = Task::count();
        $activeUsers = User::online()->count();
        $activeUsersData = User::online()->get();
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

        // leader
        $projectTotal = Project::where('created_by', auth()->id())->count() ?? 0;
        $projectAktif = Project::where('created_by', auth()->id())->where('status', 'in_progress')
            ->latest()
            ->paginate(2);
        $projectAktifData = Project::where('status', 'in_progress')->count() ?? 0;
        $projectHold = Project::where('status', 'on_hold')->count() ?? 0;

        // hitung persen project
        $projectProgressData = [];

        foreach ($projectAktif as $project) {
            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks->where('status', 'completed')->count();
            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            // Tambahkan data ke array baru
            $projectProgressData[] = [
                'x' => $project->title, // Judul proyek
                'y' => $progress,       // Progress aktual
                'goals' => [
                    [
                        'name' => 'Expected',  // Progress yang diharapkan (contoh 100%)
                        'value' => 100,
                        'strokeWidth' => 2,
                        'strokeDashArray' => 2,
                        'strokeColor' => '#775DD0',
                    ],
                ],
            ];
        }

        // lainnya
        $tugasAktif = Task::where('assigned_to', auth()->id())->where('status', 'in_progress')->count() ?? 0;
        $tugasPending = Task::where('assigned_to', auth()->id())->where('status', 'pending')->count() ?? 0;
        $totalProjects = ProjectMember::where('user_id', auth()->id())->count() ?? 0;
        $pendingTasks = Task::where('assigned_to', auth()->id())->where('status', 'pending')->paginate(5);
        $inProgressTasks  = Task::where('assigned_to', auth()->id())->where('status', 'in_progress')->paginate(5);



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

            // leader
            'projectTotal' => $projectTotal,
            'projectAktif' => $projectAktif,
            'projectAktifData' => $projectAktifData,
            'projectHold' => $projectHold,
            'projectProgressData' => $projectProgressData,

            // member lain
            'tugasAktif' => $tugasAktif,
            'tugasPending' => $tugasPending,
            'totalProjects' => $totalProjects,
            'inProgressTasks' => $inProgressTasks,
            'pendingTasks' => $pendingTasks,

            // admin
            'totalAllProjects' => $totalAllProjects
        ]);
    }

    public function online()
    {
        $users = User::select("*")
            ->whereNotNull('last_seen')
            ->orderBy('last_seen', 'DESC')
            ->paginate(10);

        return view('users', compact('users'));
    }
}
