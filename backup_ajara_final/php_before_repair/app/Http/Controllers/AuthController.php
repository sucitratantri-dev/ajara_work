<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak sesuai.'
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $destination = match ($user->role) {
            'admin' => 'admin.dashboard',
            'penyelenggara' => 'organizer.dashboard',
            'mahasiswa' => 'dashboard',
            default => null,
        };

        if (!$destination) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Role akun tidak dikenali.'
                ]);
        }

        return redirect()->route($destination)
            ->with('success', 'Berhasil login. Selamat datang di AJARA!');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],

            'role' => [
                'required',
                'in:mahasiswa,penyelenggara'
            ],

            'nim' => [
                'nullable',
                'required_if:role,mahasiswa',
                'string',
                'max:30',
                'unique:users,nim'
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
            ],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'nim' => $data['nim'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        $destination = match ($user->role) {
            'penyelenggara' => 'organizer.dashboard',
            default => 'dashboard',
        };

        return redirect()->route($destination)
            ->with('success', 'Akun AJARA berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')
            ->with('success', 'Kamu berhasil logout.');
    }
}