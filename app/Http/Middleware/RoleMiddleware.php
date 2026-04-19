<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!isset($user->role_id) || !Schema::hasTable('roles')) {
            return redirect()->route('dashboard');
        }

        $roleName = DB::table('roles')
            ->where('id_role', $user->role_id)
            ->value('nama_role');

        if ($roleName !== $role) {
            // Redirect based on actual role
            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($roleName === 'guru') {
                return redirect()->route('guru.dashboard');
            } elseif ($roleName === 'orang_tua') {
                // return redirect()->route('orangtua.dashboard'); // Commented for now
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}