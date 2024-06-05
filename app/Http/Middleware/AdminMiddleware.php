<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // التأكد من أن المستخدم مسجل دخول
        if (Auth::check() && Auth::user()->states == "states") {
            return $next($request);
        }

        // إذا لم يكن المستخدم مشرفًا، يتم رفض الطلب
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
