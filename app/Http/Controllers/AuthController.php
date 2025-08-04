<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class AuthController extends Controller
{
    // Display login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle the login process
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to log in the user using Laravel's built-in Auth
        // Laravel's Auth::attempt automatically handles the hashing
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('redirect.dashboard');
        }

        // If login fails, return back with an error
        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // Display register page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle the registration process
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Use Hash::make to hash the password
            'role'     => 'reader', // default role
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Redirect based on role
    public function redirectDashboard()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin'  => redirect('/admin/dashboard'),
            'author' => redirect('/author/dashboard'),
            'reader' => redirect('/'),
            default  => abort(403),
        };
    }
}
