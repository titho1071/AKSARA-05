<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$loginField => $validated['login'], 'password' => $validated['password']];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectAfterLogin(Auth::user());
        }

        return back()
            ->withInput($request->only('login'))
            ->with('error', 'Email/Username atau password salah');
    }

    protected function redirectAfterLogin($user)
    {
        if (isset($user->role_id) && Schema::hasTable('roles')) {
            $roleName = DB::table('roles')
                ->where('id_role', $user->role_id)
                ->value('nama_role');

            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($roleName === 'guru') {
                return redirect()->route('guru.dashboard');
            }

            if (in_array(strtolower($roleName), ['orang_tua', 'orangtua', 'orang tua'], true)) {
                return redirect()->route('orangtua.dashboard');
            }
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function apiLogin(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$loginField => $validated['login'], 'password' => $validated['password']];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'user' => $user,
                'redirect' => $this->getRedirectUrl($user),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email/Username atau password salah',
        ], 401);
    }

    protected function getRedirectUrl($user)
    {
        if (isset($user->role_id) && Schema::hasTable('roles')) {
            $roleName = DB::table('roles')
                ->where('id_role', $user->role_id)
                ->value('nama_role');

            if ($roleName === 'admin') {
                return route('admin.dashboard');
            }

            if ($roleName === 'guru') {
                return route('guru.dashboard');
            }

            if (in_array(strtolower($roleName), ['orang_tua', 'orangtua', 'orang tua'], true)) {
                return route('dashboard'); // fallback
            }
        }

        return route('dashboard');
    }
}