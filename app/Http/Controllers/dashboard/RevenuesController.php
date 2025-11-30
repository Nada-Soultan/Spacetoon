<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revenue;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;



class RevenuesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:revenues-read')->only('index', 'show');
        $this->middleware('permission:revenues-create')->only('create', 'store');
        $this->middleware('permission:revenues-update')->only('edit', 'update');
        $this->middleware('permission:revenues-delete|revenues-trash')->only('destroy', 'trashed');
        $this->middleware('permission:revenues-restore')->only('restore');
    }

    public function index(request $request)
    {

        if (!$request->has('from') || !$request->has('to')) {
            $request->merge(['from' => Carbon::now()->subDay(30)->toDateString()]);
            $request->merge(['to' => Carbon::now()->toDateString()]);
        }

        $revenues = Revenue::query()
    ->when($request->from && $request->to, function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->from)
          ->whereDate('created_at', '<=', $request->to);
    })
        ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);

        return view('dashboard.revenues.index')->with('revenues', $revenues);
    }


    public function create()
    {

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'student');
        })->get();


        return view('dashboard.revenues.create', compact('users'));
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
            'revenue_type' => "required|string",
            'user_id'=>"nullable|string",
            'revenue_amount' => "required|integer",
            'user_id'=>'required'
        ]);


        $revenue = Revenue::create([
            'revenue_type' => $request['revenue_type'],
            'revenue_amount' => $request['revenue_amount'],
            'user_id'=>$request['user_id'],
            'notes' => $request['notes'],

        ]);

        alertSuccess('revenue created successfully', 'تم إضافة الفصل بنجاح');
        return redirect()->route('revenues.index');
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
    public function edit($revenue)
    {

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'student');
        })->get();


        $revenue = Revenue::findOrFail($revenue);
        return view('dashboard.revenues.edit ',compact('revenue','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revenue $revenue)
    {
        // $class = ClassModel::findOrFail($class);

        $request->validate([
            'revenue_type' => "required|string",
            'user_id'=>"nullable|string",
            'revenue_amount' => "required|integer",

        ]);


        $revenue->update([
            'revenue_type' => $request['revenue_type'],
            'revenue_amount' => $request['revenue_amount'],
            'user_id'=>$request['user_id'],

            'notes' => $request['notes'],

        ]);



        alertSuccess('revenue updated successfully', 'تم تعديل الفصل بنجاح');
        return redirect()->route('revenues.index');
    }


}
