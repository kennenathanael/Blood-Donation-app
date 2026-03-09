<?php
// ============================================================
// app/Http/Middleware/IsAdmin.php
// ============================================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admin only area.');
        }
        return $next($request);
    }
}

// ============================================================
// app/Http/Middleware/IsDonor.php
// ============================================================
// namespace App\Http\Middleware;
// use Closure; use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// class IsDonor {
//     public function handle(Request $request, Closure $next) {
//         if (!Auth::check() || !Auth::user()->isDonor()) {
//             abort(403);
//         }
//         return $next($request);
//     }
// }
