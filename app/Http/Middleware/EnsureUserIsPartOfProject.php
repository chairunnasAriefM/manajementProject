<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPartOfProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil ID dari parameter route
        $projectId = $request->route('project');

        $project = Project::find($projectId);

        if (!$project) {
            abort(404, 'Project not found.');
        }

        $user = Auth::user();

        // Admin bypass
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Validasi jika user bukan bagian dari proyek
        if (!$project->members->contains('id', $user->id)) {
            abort(403, 'You are not authorized to access this project.');
        }

        return $next($request);
    }
}
