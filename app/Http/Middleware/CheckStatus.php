<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user->status == 1) {
            alertError('Your account has been banned for violating our usage policy. Please check with technical support to find out the reason for the ban', 'تم حظر حسابك لمخالفة سياسية الاستخدام لدينا يرجى مراجعة الدعم الفني لمعرفة سبب الحظر');
            Auth::logout();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
