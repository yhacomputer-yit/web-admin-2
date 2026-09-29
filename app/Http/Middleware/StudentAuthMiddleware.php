<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentAuthMiddleware
{
    /**
     * Only active students on the "student" guard may reach the student area.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $student = Auth::guard('student')->user();

        if (!$student || !$student->isActive()) {
            Auth::guard('student')->logout();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login')->withErrors([
                'username' => 'Your account is inactive. Please contact the admin.',
            ]);
        }

        return $next($request);
    }
}
