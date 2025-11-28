<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function LoginForm()
    {
        return view('layouts.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'error' => 'Username atau password salah!',
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Cek status user
        if ($user->status !== 'aktif') {
            Auth::logout();
            return back()->withErrors([
                'error' => 'Akun Anda belum aktif, hubungi admin!',
            ])->withInput();
        }

        // Arahkan sesuai role
        if ($user->role_user === 'admin') {
            return redirect()->route('dashboard');
        } elseif ($user->role_user === 'karyawan') {
            return redirect()->route('dashboard.karyawan');
        }

        return redirect()->route('login')->withErrors(['error' => 'Role tidak dikenali!']);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
