<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:users-read')->only('index', 'show', 'trashed');
        $this->middleware('permission:users-create')->only('create', 'store');
        $this->middleware('permission:users-update')->only('edit', 'update');
        $this->middleware('permission:users-delete|users-trash')->only('destroy', 'trashed');
        $this->middleware('permission:users-restore')->only('restore');
    }

    public function index(Request $request)
    {
        if (!$request->has('from') || !$request->has('to')) {
            $request->merge(['from' => Carbon::now()->subYear()->toDateString()]);
            $request->merge(['to' => Carbon::now()->toDateString()]);
        }

    $roles = Role::where('name', '!=', 'superadministrator')->get();

    $users = User::query()
    ->when($request->from && $request->to, function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->from)
          ->whereDate('created_at', '<=', $request->to);
    })
    ->whereHas('roles', function ($q) {
        $q->whereIn('name', ['administrator', 'teacher', 'student']);
    })
    ->whenSearch($request->search)
    ->whenRole($request->role_id)
    ->whenStatus($request->status)
    ->with('roles')
    ->latest()
    ->paginate(100);


        return view('dashboard.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::WhereRoleNot(['superadministrator', 'administrator'])->get();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
       
            $request->validate([
                'name' => "required|string|max:255",
                'email' => "required|string|email|max:255|unique:users",
                'password' => "required|string|min:8|confirmed",
                'phone' => "required|string|unique:users",
                'gender' => "required",
                'profile' => "image",
                'role' => "required|string"
            ]);

            if (!$request->profile) {
                $profile = $request->gender === 'male'
                    ? 'avatarmale.png'
                    : 'avatarfemale.png';
            } else {
                Image::make($request->profile)
                    ->resize(300, null, fn($c) => $c->aspectRatio())
                    ->save(public_path('storage/images/users/' . $request->profile->hashName()), 80);

                $profile = $request->profile->hashName();
            }
 try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'phone' => $request->phone,
                'gender' => $request['gender'],
                'profile' => $profile,
            ]);

            $role = Role::firstOrCreate(
                ['name' => 'administrator'],
                ['display_name' => 'administrator', 'description' => 'administrator']
            );

            if (in_array($request->role, ['teacher', 'student'])) {
                $user->addRoles([$request->role]);
            } else {
                $user->addRoles(['administrator', $request->role]);
            }

            alertSuccess('user created successfully', 'تم إضافة المستخدم بنجاح');
            return redirect()->route('users.index');

      } catch (ValidationException $e) {
    return redirect()->back()->withErrors($e->errors())->withInput();
} catch (\Exception $e) {
    Log::error("User creation error: " . $e->getMessage());
    alertError("Something went wrong while creating user.", "حدث خطأ أثناء إنشاء المستخدم");
    return redirect()->back()->withInput();
}
    }

    public function edit($id)
    {
        $roles = Role::WhereRoleNot(['superadministrator', 'administrator'])->get();
        $user = User::findOrFail($id);
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => "required|string|max:255",
                'email' => "required|string|email|max:255|unique:users,email," . $user->id,
                'phone' => "required|string|unique:users,phone," . $user->id,
                'gender' => "required",
                'profile' => "image",
                'password' => "nullable|string|min:8|confirmed",
            ]);

            if ($request->hasFile('profile')) {

                if (!in_array($user->profile, ['avatarmale.png', 'avatarfemale.png'])) {
                    Storage::disk('public')->delete('/images/users/' . $user->profile);
                }

                Image::make($request->profile)
                    ->resize(300, null, fn($c) => $c->aspectRatio())
                    ->save(public_path('storage/images/users/' . $request->profile->hashName()), 60);

                $user->update(['profile' => $request->profile->hashName()]);
            }

            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request->phone,
                'gender' => $request['gender'],
                'password' => $request->password
                    ? Hash::make($request['password'])
                    : $user->password,
            ]);

          if ($request->role === 'user_id') {
    $user->syncRoles([$request->role]);
} else {
    $user->syncRoles(['administrator', $request->role]);
}

            alertSuccess('user updated successfully', 'تم تعديل المستخدم بنجاح');
            return redirect()->route('users.index');

        } catch (\Exception $e) {
            Log::error("User update error: " . $e->getMessage());
            alertError("Something went wrong while updating user.", "حدث خطأ أثناء تعديل المستخدم");
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::withTrashed()->where('id', $id)->first();

            if ($user->trashed() && auth()->user()->hasPermission('users-delete')) {

                if (!in_array($user->profile, ['avatarmale.png', 'avatarfemale.png'])) {
                    Storage::disk('public')->delete('/images/users/' . $user->profile);
                }

                $user->forceDelete();

                alertSuccess('user deleted successfully', 'تم حذف المستخدم نهائياً');
                return redirect()->route('users.trashed');

            } elseif (!$user->trashed() && auth()->user()->hasPermission('users-trash') && checkUserForTrash($user)) {

                $user->delete();
                alertSuccess('user trashed successfully', 'تم حذف المستخدم مؤقتاً');
                return redirect()->route('users.index');
            }

            alertError('Invalid permission or user cannot be deleted.', 'لا يمكنك حذف هذا المستخدم');
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error("User delete error: " . $e->getMessage());
            alertError("Something went wrong while deleting.", "حدث خطأ أثناء الحذف");
            return redirect()->back();
        }
    }

    public function trashed()
    {
        $roles = Role::where('name', '!=', 'superadministrator')->get();


        $users = User::onlyTrashed()
           ->whereDoesntHave('roles', function ($q) {
        $q->where('name', 'superadministrator');
    })
            ->whenSearch(request()->search)
            ->whenRole(request()->role_id)
            ->whenStatus(request()->status)
            ->with('roles')
            ->latest()
            ->paginate(100);

        return view('dashboard.users.index', compact('users', 'roles'));
    }

    public function restore($id)
    {
        try {
            User::withTrashed()->where('id', $id)->first()->restore();
            alertSuccess('user restored successfully', 'تم استعادة المستخدم');
            return redirect()->route('users.index');

        } catch (\Exception $e) {
            Log::error("User restore error: " . $e->getMessage());
            alertError("Failed to restore user.", "فشل استعادة المستخدم");
            return redirect()->back();
        }
    }

    public function activate(User $user)
    {
        try {
            if (hasVerifiedPhone($user)) {
                $user->forceFill(['phone_verified_at' => null])->save();
            } else {
                markPhoneAsVerified($user);
            }

            return redirect()->route('users.index');
        } catch (\Exception $e) {
            Log::error("User activation error: " . $e->getMessage());
            alertError("Error updating phone verification.", "خطأ في تحديث حالة التحقق");
            return redirect()->back();
        }
    }

    public function block(User $user)
    {
        try {
            $user->forceFill([
                'status' => $user->status == 0 ? 1 : 0,
            ])->save();

            return redirect()->route('users.index');

        } catch (\Exception $e) {
            Log::error("User block error: " . $e->getMessage());
            alertError("Error updating user status.", "خطأ في تغيير حالة المستخدم");
            return redirect()->back();
        }
    }

    
}
