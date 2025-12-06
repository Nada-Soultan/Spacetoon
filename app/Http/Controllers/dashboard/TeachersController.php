<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Role;
use App\Models\User;
use App\Models\Teacher;


class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:classes-read')->only('index', 'show');
        $this->middleware('permission:classes-create')->only('create', 'store');
        $this->middleware('permission:classes-update')->only('edit', 'update');
        $this->middleware('permission:classes-delete|classes-trash')->only('destroy', 'trashed');
        $this->middleware('permission:classes-restore')->only('restore');
    }





    public function index()
    {

        $teachers = Teacher::whereHas('user', function ($q) {
        $q->where('status', 0)
        ->where('phone_verified_at','!=',null); // Only active users
    })
    ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);


        return view('dashboard.teachers.index')->with('teachers', $teachers);
    }

   


   /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            })->get();

 // Initialize deduction and net salary to default values (or empty)
//  $deductions = 1;
//  $netSalary = 0;

//  // Check if the form has been submitted
//  if ($request->isMethod('post')) {
//      // Calculate deduction based on form input values
//      $salary = $request->input('salary');
//      $attendance = $request->input('attendance');
//      $deductions = $salary * 12 / 365 * $attendance;

//      // Get the fees_of_courses from the form
//      $feesOfCourses = $request->input('fees_of_courses');

//      // Calculate net salary (salary - deduction + fees_of_courses)
//      $netSalary = $salary - $deductions + $feesOfCourses;
//  }

 return view('dashboard.teachers.create', compact('users'));
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
                'fees_of_courses'=>"required|integer",
                'extra_courses'=>"required|string",
                'salary'=>"required|integer",



            ]);


            $teacher = Teacher::create([
                'user_id' => $request['user_id'],
                'fees_of_courses' => $request['fees_of_courses'],
                'extra_courses' => $request['extra_courses'],
                'salary'=>$request['salary'],




            ]);

            alertSuccess('teacher created successfully', 'تم إضافة الفصل بنجاح');
            return redirect()->route('teachers.index');
        }

       



        /**
         * Show the form for editing the specified resource.
         *
         *          * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response

         */
        public function edit( Teacher $teacher )
    {




        // Retrieve all users with the teacher role
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->get();

         // Find the teacher by ID


        // Retrieve all classes

//         // Initialize deduction to an empty value (or any default value you prefer)
//         $deductions = 1;
//  $netSalary = 0;

//  // Check if the form has been submitted
//  if ($request->isMethod('post')) {
//     // Calculate deduction based on form input values
//     $salary = $request->input('salary');
//     $attendance = $request->input('attendance');
//     $deductions = $salary * 12 / 365 * $attendance;

//     // Get the fees_of_courses from the form
//     $feesOfCourses = $request->input('fees_of_courses');

//     // Calculate net salary (salary - deduction + fees_of_courses)
//     $netSalary = $salary - $deductions + $feesOfCourses;
// }

return view('dashboard.teachers.edit', compact('teacher','users'));
}

public function update(request $request ,Teacher $teacher)

{

    $request -> validate([
        'user_id' => "required|integer",
                'fees_of_courses'=>"required|integer",
                'extra_courses'=>"required|string",
                'salary'=>"required|integer",

    ]);

    $teacher -> update([

        'user_id'=> $request['user_id'],
        'fees_of_courses'=> $request['fees_of_courses'],
        'extra_courses'=> $request['extra_courses'],
        'salary'=> $request['salary'],
    ]);

    alertSuccess('teacher updated successfully', 'تم تعديل المدرس بنجاح');
    return redirect()->route('teachers.index');

}



}

