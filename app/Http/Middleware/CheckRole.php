<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check karna ke user login hai aur us ka role sahi hai ya nahi
        if (!session()->has('user_role') || session('user_role') !== $role) {
            return redirect('/')->with('error', 'Sir, aap ko is page ki ijazat nahi hai!');
        }

        return $next($request);
    }
}