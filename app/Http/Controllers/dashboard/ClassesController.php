<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Role;
use App\Models\User;



class ClassesController extends Controller
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

            $classes = ClassModel::whenSearch(request()->search)
            ->with('user')
                ->latest()
                ->paginate(100);

            return view('dashboard.classes.index')->with('classes', $classes);
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
            return view('dashboard.classes.create', compact( 'users'));
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
                'class_name' => "required|string|max:255|unique:classes",
                'user_id' => "required|string|max:255",
                'class_stage'=>"required|integer",
                'students_no'=>"required|integer",
            ]);


            $class = ClassModel::create([
                'class_name' => $request['class_name'],
                'user_id' => $request['user_id'],
                'class_stage' => $request['class_stage'],
                'students_no' => $request['students_no'],

            ]);

            alertSuccess('class created successfully', 'تم إضافة الفصل بنجاح');
            return redirect()->route('classes.index');
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($class)
        {
            $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            })->get();
            $class = ClassModel::findOrFail($class);
            return view('dashboard.classes.edit ',compact('class', 'users'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, ClassModel $class)
        {
            // $class = ClassModel::findOrFail($class);

            $request->validate([
                'class_name' => "required|string|max:255" ,
                'user_id' => "required|string|max:255" ,
                'class_stage'=>"required|integer" ,
                'students_no'=>"required|integer" ,

            ]);


            $class->update([
                'class_name' => $request['class_name'],
                'user_id' => $request['user_id'],
                'class_stage' => $request['class_stage'],
                'students_no' => $request['students_no'],
            ]);



            alertSuccess('class updated successfully', 'تم تعديل الفصل بنجاح');
            return redirect()->route('classes.index');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($class)
        {
            $class = ClassModel::withTrashed()->where('id', $class)->first();
            if ($class->trashed() && auth()->user()->hasPermission('classes-delete')) {
                $class->forceDelete();
                alertSuccess('class deleted successfully', 'تم حذف السنترال بنجاح');
                return redirect()->route('classes.trashed');
            } elseif (!$class->trashed() && auth()->user()->hasPermission('classes-trash') ) {
                $class->delete();
                alertSuccess('class trashed successfully', 'تم حذف السنترال مؤقتا');
                return redirect()->route('classes.index');
            } else {
                alertError('Sorry, you do not have permission to perform this action, or the class cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو السنترال لا يمكن حذفه حاليا');
                return redirect()->back();
            }
        }

        public function trashed()
        {
            $classes = ClassModel::onlyTrashed()
                ->whenSearch(request()->search)
                ->latest()
                ->paginate(100);
            return view('dashboard.classes.index', ['classes' => $classes]);
        }

        public function restore($class, Request $request)
        {
            $class = ClassModel::withTrashed()->where('id', $class)->first()->restore();
            alertSuccess('class restored successfully', 'تم استعادة السنترال بنجاح');
            return redirect()->route('classes.index', ['parent_id' => $request->parent_id]);
        }
    }


