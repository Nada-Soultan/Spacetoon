<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(request $request)
    {



        // 1) If month & year are selected → generate from/to
        if ($request->filled('month') && $request->filled('year')) {
            $from = Carbon::create($request->year, $request->month, 1)->startOfDay();
            $to   = Carbon::create($request->year, $request->month, 1)->endOfMonth()->endOfDay();

            $request->merge([
                'from' => $from->toDateString(),
                'to'   => $to->toDateString(),
            ]);
        }

        // 2) Default dates when nothing selected → current month
        if (!$request->filled('from') || !$request->filled('to')) {
            $request->merge([
                'from' => Carbon::now()->startOfMonth()->toDateString(),
                'to'   => Carbon::now()->endOfMonth()->toDateString(),
            ]);
        }

        $user = Auth::user();

        return view('dashboard.home', compact('user'));
    }
}
    
