<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;



class ExpensesController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:expenses-read')->only('index', 'show');
        $this->middleware('permission:expenses-create')->only('create', 'store');
        $this->middleware('permission:expenses-update')->only('edit', 'update');
        $this->middleware('permission:expenses-delete|expenses-trash')->only('destroy', 'trashed');
        $this->middleware('permission:expenses-restore')->only('restore');
    }

    public function index(request $request)
    {

        if (!$request->has('from') || !$request->has('to')) {
            $request->merge(['from' => Carbon::now()->subDay(30)->toDateString()]);
            $request->merge(['to' => Carbon::now()->toDateString()]);
        }

        $expenses = Expense::query()
    ->when($request->from && $request->to, function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->from)
          ->whereDate('created_at', '<=', $request->to);
    
    })
    ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);

        return view('dashboard.expenses.index')->with('expenses', $expenses);
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


        return view('dashboard.expenses.create',compact('users'));
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
            'expense_type' => "required|string",
            'user_id' => 'required_if:expense_type,teacher_salary',
            'expense_amount' => "required|integer",
        ]);


        $expense = Expense::create([
            'expense_type' => $request['expense_type'],
            'expense_amount' => $request['expense_amount'],
            'user_id'=>$request['user_id'],
            'notes' => $request['notes'],

        ]);

        alertSuccess('expense created successfully', 'تم إضافة الفصل بنجاح');
        return redirect()->route('expenses.index');
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
    public function edit($expense)
    {


        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->get();


        $expense = Expense::findOrFail($expense);
        return view('dashboard.expenses.edit ',compact('expense','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        // $class = ClassModel::findOrFail($class);

        $request->validate([
            'expense_type' => "required|string",
            'user_id'=>"nullable|string",

            'expense_amount' => "required|integer",
            'notes'=>"required|string",


        ]);


        $expense->update([
            'expense_type' => $request['expense_type'],
            'expense_amount' => $request['expense_amount'],
            'user_id'=>$request['user_id'],

            'notes' => $request['notes'],

        ]);



        alertSuccess('expense updated successfully', 'تم تعديل الفصل بنجاح');
        return redirect()->route('expenses.index');
    }

}
