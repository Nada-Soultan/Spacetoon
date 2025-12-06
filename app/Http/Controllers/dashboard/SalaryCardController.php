<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Teacher;



class SalaryCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:cards-read')->only('index', 'show');
}


 public function index(Request $request)
    {
        // defaults
        $year  = (int) ($request->input('year', now()->year));
        $month = (int) ($request->input('month', now()->month));

        // sanitize month/year
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        if ($year < 1970 || $year > now()->year + 5) {
            $year = now()->year;
        }

        // date range for the selected month
        $from = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $to   = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

        // fetch teachers (eager load user relation)
        $teachers = Teacher::with('user')
            ->whenSearch($request->input('search'))
            ->get();

        // compute salary rows (one row per teacher for selected month)
        $salaryCards = [];

        foreach ($teachers as $teacher) {
            $daysOff   = getDayOff($from, $to, $teacher->user_id);
            $hoursOff  = getHoursOff($from, $to, $teacher->user_id);

            $dailySalary = getDay($teacher->salary);
            $hourValue   = $dailySalary / 8;

            $deduction = ($daysOff * $dailySalary) + ($hoursOff * $hourValue);
            $netSalary = $teacher->salary - $deduction;

            $salaryCards[] = [
                'teacher'      => $teacher,
                'days_off'     => $daysOff,
                'hours_off'    => $hoursOff,
                'daily_salary' => round($dailySalary, 2),
                'base_salary'  => round($teacher->salary, 2),
                'deduction'    => round($deduction, 2),
                'net_salary'   => round($netSalary, 2),
            ];
        }

        return view('dashboard.cards.index', compact('salaryCards', 'year', 'month', 'teachers'));
    }
}

