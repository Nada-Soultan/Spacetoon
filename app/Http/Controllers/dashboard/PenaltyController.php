<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penalty;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PenaltyController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');

        $this->middleware('permission:penalty-read')->only('index', 'show');
        $this->middleware('permission:penalty-create')->only('create', 'store');
        $this->middleware('permission:penalty-update')->only('edit', 'update');
        $this->middleware('permission:penalty-delete')->only('destroy');
    }

    public function index(request $request)
    {

              if (!$request->has('from') || !$request->has('to')) {
            $request->merge(['from' => Carbon::now()->subDay(30)->toDateString()]);
            $request->merge(['to' => Carbon::now()->toDateString()]);
        }

        $penalties = Penalty::query()
    ->when($request->from && $request->to, function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->from)
          ->whereDate('created_at', '<=', $request->to);
    })
        ->whenSearch(request()->search)
        ->latest()
        ->paginate(50);

        return view('dashboard.penalty.index', compact('penalties'));
    }

    public function create()
    {
        $users = User::where('status', 0)
            ->where('phone_verified_at', '!=', null)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            })
            ->get();

        return view('dashboard.penalty.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:morning_delay,other',
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:500',
            'minutes' => 'nullable|integer|min:0',
            'hours' => 'nullable|integer|min:0',
            'date' => 'nullable|date',
        ]);

        try {
            $penalty = Penalty::create([
                'type' => $request->type,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
                'minutes' => $request->minutes,
                'hours' => $request->hours,
                'date' => $request->date ?? now(),
            ]);

            Log::info("Penalty created", ['id' => $penalty->id]);

            alertSuccess('Penalty added successfully', 'تم إضافة الخصم بنجاح');
            return redirect()->route('penalty.index');

        } catch (\Exception $e) {
            Log::error("Penalty create error: " . $e->getMessage());
            alertError('Something went wrong', 'حدث خطأ أثناء الإضافة');
            return back()->withInput();
        }
    }

    public function edit(Penalty $penalty)
    {
        $users = User::where('status', 0)
            ->where('phone_verified_at', '!=', null)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            })
            ->get();

        return view('dashboard.penalty.edit', compact('penalty', 'users'));
    }

    public function update(Request $request, Penalty $penalty)
    {
        $request->validate([
            'type' => 'required|in:morning_delay,other',
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:500',
            'minutes' => 'nullable|integer|min:0',
            'hours' => 'nullable|integer|min:0',
            'date' => 'nullable|date',
        ]);

        try {
            $penalty->update([
                'type' => $request->type,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
                'minutes' => $request->minutes,
                'hours' => $request->hours,
                'date' => $request->date ?? now(),
            ]);

            Log::info("Penalty updated", ['id' => $penalty->id]);

            alertSuccess('Penalty updated successfully', 'تم تعديل الخصم بنجاح');
            return redirect()->route('penalty.index');

        } catch (\Exception $e) {
            Log::error("Penalty update error: " . $e->getMessage());
            alertError('Something went wrong', 'حدث خطأ أثناء التعديل');
            return back()->withInput();
        }
    }

    public function destroy(Penalty $penalty)
    {
        try {
            $penalty->delete();

            alertSuccess('Penalty deleted successfully', 'تم حذف الخصم بنجاح');
            return redirect()->route('penalty.index');

        } catch (\Exception $e) {
            Log::error("Penalty delete error: " . $e->getMessage());
            alertError('Error deleting penalty', 'حدث خطأ أثناء الحذف');
            return back();
        }
    }
}
