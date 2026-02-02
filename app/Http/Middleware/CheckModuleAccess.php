<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect('login');
        }

        // Admins have access to everything
        if ($user->role === 'admin') {
            return $next($request);
        }

        $moduleAccess = $user->module_access ?? [];
        
        if (!in_array($module, $moduleAccess)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You do not have access to this module.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'You do not have access to the ' . str_replace('_', ' ', $module) . ' module.');
        }

        return $next($request);
    }
}
