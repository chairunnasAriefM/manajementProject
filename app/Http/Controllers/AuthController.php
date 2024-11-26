<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AuthController extends Controller
{

    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.index');
    }

    // Tampilkan form register
    public function showRegisterForm()
    {
        return view('auth.index');
    }

    // Logika Register
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }

    // Logika Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            // Set cookie jika remember me dipilih
            if ($remember) {
                Cookie::queue('email', $request->email, 60 * 24 * 30); // 30 hari
            }
            return redirect('/dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    // Logika Logout
    public function logout(Request $request)
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('username'));

        return redirect('/')->with('logout_success', 'Logout Berhasil!');
    }
}
