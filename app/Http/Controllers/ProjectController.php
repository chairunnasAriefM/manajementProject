<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $projects = Project::with('members')->when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);

        $users = User::all();

        return view('admin.manage_projects.showProjects', compact('projects', 'users'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('leader.manage_projects.addProject', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'members' => 'nullable|array|exists:users,id'
        ]);

        // Buat proyek baru
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'in_progress',
            'created_by' => Auth::id(),
        ]);

        // Simpan anggota proyek jika ada anggota yang dipilih
        if ($request->has('members')) {
            foreach ($request->members as $userId) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $userId,
                ]);
            }
        }

        return redirect()->route('projects.create')->with('success', 'Proyek Berhasil dibuat!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Project $project) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'members' => 'nullable|array',
        ]);

        $project = Project::findOrFail($id);

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);


        if ($request->has('members')) {
            $project->members()->sync($request->members);
        } else {
            $project->members()->sync([]);
        }

        return response()->json(['success' => true, 'message' => 'Proyek Berhasil diupdate!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['success' => true, 'message' => 'Proyek Berhasil dihapus!']);
    }

    public function perLeader(Request $request)
    {
        $userId = Auth::id();

        $search = $request->input('search');
        $projects = Project::with('members')->when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->where('created_by', $userId)->paginate(10);

        $users = User::all();

        return view('leader.manage_projects.showProjects', compact('projects', 'users'));
    }

    public function removeMember(Request $request, $projectId)
    {
        $request->validate([
            'memberId' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($projectId);
        $memberId = $request->memberId;

        $project->members()->detach($memberId);

        return response()->json(['success' => true, 'message' => 'Anggota berhasil dihapus dari proyek']);
    }
}
