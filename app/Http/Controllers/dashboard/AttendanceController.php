<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Attendance;




class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:attendance-read')->only('index', 'show');
        $this->middleware('permission:attendance-create')->only('create', 'store');
        $this->middleware('permission:attendance-update')->only('edit', 'update');
        $this->middleware('permission:attendance-delete|classes-trash')->only('destroy', 'trashed');
        $this->middleware('permission:attendance-restore')->only('restore');
    }

public function index()
{
    $month = request()->get('month', now()->month);
    $year  = request()->get('year', now()->year);

    $attendances = Attendance::whenSearch(request()->search)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->latest()
        ->paginate(100);

    return view('dashboard.attendance.index', compact('attendances', 'month', 'year'));
}


    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })
        ->whereNotNull('phone_verified_at')
        ->get();


        return view('dashboard.attendance.create', compact('users'));
    }


    /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {



            $request->validate([
                'user_id' => "required|integer",
                'absence_date'=>"required|date",
                'no_of_hours'=>"required|integer",
                'status'=>"required",
                'reasons'=>"string",
                'comments'=>"string",

            ]);

            $attendance = Attendance::create([
                'user_id' => $request['user_id'],
                'absence_date' => $request['absence_date'],
                'status' => $request['status'],
                'reasons'=>$request['reasons'],
                'comments'=>$request['comments'],
                'no_of_hours'=>$request['no_of_hours'],

            ]);

            alertSuccess('attendance created successfully', 'تم إضافة الفصل بنجاح');
            return redirect()->route('attendance.index');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($attendance)
        {
            $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            })
            ->whereNotNull('phone_verified_at')
            ->get();

            $attendance = Attendance::findOrFail($attendance);
            return view('dashboard.attendance.edit ',compact('attendance', 'users'));
        }


        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, Attendance $attendance)
        {

            $request->validate([
                'user_id' => "required|integer",
                'absence_date'=>"required|date",
                'no_of_hours'=>"required|integer",
                'status'=>"required",
                'reasons'=>"string",
                'comments'=>"string",


            ]);


            $attendance->update([

                    'user_id' => $request['user_id'],
                    'absence_date' => $request['absence_date'],
                    'status' => $request['status'],
                    'reasons'=>$request['reasons'],
                    'comments'=>$request['comments'],
                    'no_of_hours'=>$request['no_of_hours'],


            ]);



            alertSuccess('attendance updated successfully', 'تم تعديل الفصل بنجاح');
            return redirect()->route('attendance.index');
        }

 public function destroy(Attendance $attendance)
    {
        $attendance->delete(); // Soft delete
        alertSuccess('Attendance deleted successfully', 'تم حذف الفصل بنجاح');
        return redirect()->route('attendance.index');
    }
public function trashed()
{
    // Provide default month/year so the view has them
    $month = now()->month;
    $year = now()->year;

    $attendances = Attendance::onlyTrashed()->latest()->paginate(50);

    return view('dashboard.attendance.index', compact('attendances', 'month', 'year'));
}


    // Restore soft-deleted attendance
    public function restore($id)
    {
        $attendance = Attendance::onlyTrashed()->findOrFail($id);
        $attendance->restore();

        alertSuccess('Attendance restored successfully', 'تم استعادة الفصل بنجاح');
        return redirect()->route('attendance.index');
    }


}
