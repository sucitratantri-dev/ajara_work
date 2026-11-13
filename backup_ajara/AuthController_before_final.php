
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Halaman registrasi
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Halaman lupa password
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {

            $request->session()->regenerate();

            $user = Auth::user();

            // Arahkan berdasarkan role
            $destination = match ($user->role) {
                'admin' => 'admin.dashboard',
                'penyelenggara' => 'organizer.dashboard',
                'mahasiswa' => 'dashboard',
                default => 'dashboard',
            };

            return redirect()
                ->route($destination)
                ->with(
                    'success',
                    'Berhasil login. Selamat datang di AJARA!'
                );
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])
            ->onlyInput('email');
    }

    /**
     * Proses registrasi akun
     */
    public function register(Request $request)
    {
        // Default role adalah mahasiswa
        $request->merge([
            'role' => $request->input('role') ?: 'mahasiswa',
        ]);

        $data = $request->validate([

            'name' => [
                'required',
                'string',
                'max:120',
            ],

            'nim' => [
                'nullable',
                'required_if:role,mahasiswa',
                'string',
                'max:30',
                'unique:users,nim',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],

            'role' => [
                'required',
                'in:mahasiswa,penyelenggara',
            ],
        ]);

        // Membuat akun baru
        $user = User::create([

            'name' => $data['name'],

            'nim' => $data['nim'] ?? null,

            'email' => $data['email'],

            'password' => Hash::make($data['password']),

            'role' => $data['role'],

        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        $request->session()->regenerate();

        // Redirect sesuai role
        $destination = $user->role === 'penyelenggara'
            ? 'organizer.dashboard'
            : 'dashboard';

        return redirect()
            ->route($destination)
            ->with(
                'success',
                'Akun AJARA berhasil dibuat. Selamat bergabung!'
            );
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('welcome')
            ->with(
                'success',
                'Kamu berhasil logout dari AJARA.'
            );
    }
}
