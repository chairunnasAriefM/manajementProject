<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect('/addnewuser')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroyUser($id)
    {
        try {
            $data = User::findOrFail($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ignore the current user's email
            'role' => 'required|in:admin,leader,programmer,3dArtist,music composer,2dArtist,member',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json(['success' => true, 'message' => 'User updated successfully!']);
    }
}
