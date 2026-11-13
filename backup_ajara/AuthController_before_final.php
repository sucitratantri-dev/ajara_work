<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function showForgotPassword() { return view('auth.forgot-password'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $destination = match (auth()->user()->role) {
                'admin' => route('admin.dashboard'),
                'penyelenggara' => route('organizer.dashboard'),
                default => route('dashboard'),
            };
            return redirect()->intended($destination)->with('success', 'Berhasil login. Selamat datang di AJARA!');
        }

        return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'nim' => ['nullable', 'required_if:role,mahasiswa', 'string', 'max:30', 'unique:users,nim'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['nullable', 'in:mahasiswa,penyelenggara'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'nim' => $data['nim'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'mahasiswa',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $destination = $user->role === 'penyelenggara' ? 'organizer.dashboard' : 'dashboard';
        return redirect()->route($destination)->with('success', 'Akun AJARA berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}

