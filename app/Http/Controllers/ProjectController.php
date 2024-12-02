<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $projects = Project::when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('leader.manage_projects.showProjects', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leader.manage_projects.addProject');
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
            // 'status' => 'required|in:in_progress,completed,on_hold',
        ]);

        Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'in_progress',
            'created_by' => Auth::id(),
        ]);

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
        $validatedData =  $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:in_progress,completed,on_hold',
        ]);

        $project = Project::findOrFail($id);
        $project->update($validatedData);

        return response()->json(['success' => true, 'message' => 'Proyek Berhasil dihapus!']);
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
}
