<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function showUser(Request $request)
    {
        $search = $request->input('search');

        $data = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.manageuser.showUser', compact('data'));
    }



    public function showAddnewUserForm()
    {
        return view('admin.manageuser.addNewUser');
    }

    public function addNewUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,leader,programmer,3dArtist,music composer,2dArtist,member',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'avatar' => $avatarPath,
        ]);

        return redirect('/addnewuser')->with('success', 'User berhasil ditambahkan!');
    }


    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // Hapus gambar jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Hapus pengguna
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User  deleted successfully!',
        ]);
    }

    public function updateUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if ($request->has('password') && !empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $avatarPath;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'avatarPath' => $user->avatar_path ? asset('storage/' . $user->avatar_path) : null
        ]);
    }
}
