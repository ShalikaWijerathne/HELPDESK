<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Map short aliases (admin, staff) to the full names stored in the database
        $allowed = array_map(fn($r) => match ($r) {
            'admin' => 'Admin',
            'staff' => 'IT Staff',
            'user'  => 'User',
            default => $r,
        }, $roles);

        if (!in_array($user->role?->name, $allowed)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
