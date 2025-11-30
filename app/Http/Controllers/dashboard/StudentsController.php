<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\StudentImage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Hash;
use Symfony\Contracts\Service\Attribute\Required;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:students-read')->only('index', 'show');
        $this->middleware('permission:students-create')->only('create', 'store');
        $this->middleware('permission:students-update')->only('edit', 'update');
        $this->middleware('permission:students-delete|students-trash')->only('destroy', 'trashed');
        $this->middleware('permission:students-restore')->only('restore');
    }

    public function index()
    {

        $students = Student::whenSearch(request()->search)
        ->latest()
         ->paginate(100);



        return view('dashboard.students.index')->with('students', $students);
    }

    /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {

            $classes = ClassModel::all();



            return view('dashboard.students.create',compact('classes'));
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
                'name' => "required|string|max:255",
                'email' => "required|string|email|max:255|unique:users",
                'phone' => "required|string|unique:users",
                'gender' => "required",
                'age'=>"required|integer",
                'date_of_join'=>"required|date",
                'date_of_birth'=>"required|date",
                'images'=>"nullable|array",
                'class_id'=>"required",



            ]);


        if (!isset($request->profile)) {
            if ($request->gender == 'male') {
                $profile = 'avatarmale.png';
            } else {
                $profile = 'avatarfemale.png';
            }}


        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['phone']),
            'phone' => $request->phone,
            'gender' => $request['gender'],
            'profile' => $profile,
            'phone_verified_at' => now(),

        ]);

            $student = Student::create([
                'user_id' => $user->id,
                'age' => $request['age'],
                'date_of_join' => $request['date_of_join'],
                'date_of_birth' => $request['date_of_birth'],
                'fees_of_uniform'=>$request['fees_of_uniform'],
                'fees_of_book'=>$request['fees_of_book'],
                'class_id'=>$request['class_id'],



            ]);


            if ($request->hasFile('images') && $files = $request->file('images')) {
                foreach ($files as $file) {
                    Image::make($file)->save(public_path('storage/images/students/' . $file->hashName()), 80);
                    StudentImage::create([
                        'student_id' => $student->id,
                        'image' => $file->hashName(),
                    ]);
                }
            }


        $role = Role::where('name', 'student')->first();

        if (!$role) {
            $role = Role::create([
                'name' => 'student',
                'display_name' => 'student',
                'description' => 'student',
            ]);
        }



        $user->addRoles(['student']);


            alertSuccess('Student created successfully', 'تم إضافة الفصل بنجاح');
            return redirect()->route('students.index');
        }



        /**
         * Show the form for editing the specified resource.
         *
         *          * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response

         */
        public function edit( Student $student )
    {

        $classes = ClassModel::all();




return view('dashboard.students.edit', compact('student' ,'classes'));
}

public function update(request $request ,Student $student)

{

    $request -> validate([
        'name' => "required|string|max:255",
        'email' => "required|string|email|max:255|unique:users",
        'phone' => "required|string|unique:users",
        'gender' => "required",
        'age'=>"required|integer",
        'date_of_join'=>"required|date",
        'date_of_birth'=>"required|date",
        'fees_of_uniform'=>"integer",
        'fees_of_book'=>"integer",
        'images'=>"nullable|array",
    ]);



    if (!isset($request->profile)) {
        if ($request->gender == 'male') {
            $profile = 'avatarmale.png';
        } else {
            $profile = 'avatarfemale.png';
        }}


        if ($request->hasFile('images') && $files = $request->file('images')) {
            foreach ($files as $file) {
                Image::make($file)->save(public_path('storage/images/tasks/' . $file->hashName()), 80);
                StudentImages::create([
                    'student_id' => $student->id,
                    'image' => $file->hashName(),
                ]);
            }
        }


    $student -> update([

        'name' => $request['name'],
        'email' =>  $request['email'],
        'phone' =>  $request['phone'],
        'gender' =>  $request['gender'],
        'age'=> $request['age'],
        'date_of_join'=> $request['date_of_join'],
        'date_of_birth'=> $request['date_of_birth'],
        'fees_of_uniform'=> $request['fees_of_uniform'],
        'fees_of_book'=> $request['fees_of_book'],

    ]);

    alertSuccess('student updated successfully', 'تم تعديل المدرس بنجاح');
    return redirect()->route('students.index');

}

}

