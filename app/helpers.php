<?php

use App\Models\User;
use App\Models\Role;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\Revenue;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

// calculate date
if (!function_exists('interval')) {
    function interval($old)
    {
        $date = Carbon::now();
        return $interval = $old->diffForHumans();
    }
}


// calculate date
if (!function_exists('interval2')) {
    function interval2($old)
    {
        $old = Carbon::parse($old);
        return $interval = $old->diffForHumans();
    }
}



// alert success
if (!function_exists('alertSuccess')) {
    function alertSuccess($en, $ar)
    {
        app()->getLocale() == 'ar' ?
            session()->flash('success', $ar) :
            session()->flash('success', $en);
    }
}


// alert error
if (!function_exists('alertError')) {
    function alertError($en, $ar)
    {
        app()->getLocale() == 'ar' ?
            session()->flash('error', $ar) :
            session()->flash('error', $en);
    }
}



// check user for trash
if (!function_exists('checkUserForTrash')) {
    function checkUserForTrash($user)
    {

            return true;
        }

}


// check role for trash

if (!function_exists('checkRoleForTrash')) {
    function checkRoleForTrash($role)
    {
        if ($role->users()->withTrashed()->count() > '0') {
            return false;
        } else {
            return true;
        }
    }
}




// set localizaition in session
if (!function_exists('setLocaleBySession')) {
    function setLocaleBySession()
    {

        if (Auth::check()) {
            $user = User::findOrFail(Auth::id());

            $user->update([
                'lang' => app()->getLocale() == 'ar' ? 'en' : 'ar',
            ]);
        } else {
            app()->getLocale() == 'en' ? session(['lang' => 'ar']) : session(['lang' => 'en']);
        }
    }
}


if (!function_exists('hasVerifiedPhone')) {
    function hasVerifiedPhone($user)
    {
        return !is_null($user->phone_verified_at);
    }
}


// make phone verified
if (!function_exists('markPhoneAsVerified')) {
    function markPhoneAsVerified($user)
    {
        return $user->forceFill([
            'phone_verified_at' => $user->freshTimestamp(),
        ])->save();
    }
}


// get no of day off
if (!function_exists('getDayOff')) {
    function getDayOff( $from , $to , $user_id )
    {
        $attendance = Attendance::whereDate('absence_date', '>=', $from)
        ->whereDate('absence_date', '<=', $to)
        ->where('user_id',$user_id)
        ->where('status','dayOff')
        ->whenSearch(request()->search)
        ->get()
        ->count();
        return $attendance ;
    }
}


if (!function_exists('getHoursOff')) {
    function getHoursOff( $from , $to , $user_id )
    {
        $no_of_hours = 0;
        $attendances = Attendance::whereDate('absence_date', '>=', $from)
        ->whereDate('absence_date', '<=', $to)
        ->where('user_id',$user_id)
        ->where('status','permission')
        ->whenSearch(request()->search)
        ->get();

        foreach($attendances as $attendance){
            $no_of_hours += $attendance->no_of_hours ;
        }

        return $no_of_hours ;
    }
}


if (!function_exists('getDay')) {
    function getDay( $salary  )
    {


        return ($salary * 12) / 365 ;
    }
}


if (!function_exists('getExpenses')) {
    function getExpenses( $from , $to  )
    {
        // dd($from, $to);
        $expense_amount=0 ;

        $expenses = Expense::whereDate('created_at', '>=' , $from)
        ->whereDate('created_at', '<=' , $to)
        ->whenSearch(request()->search)
        ->get();
        
        foreach($expenses as $expense){
            $expense_amount += $expense->expense_amount ;
        }
        return $expense_amount ;
    }
}

if (!function_exists('getRevenues')) {
    function getRevenues( $from , $to  )
    {
        $revenue_amount = 0;

        $revenues = Revenue::whereDate('created_at', '>=', $from)
        ->whereDate('created_at', '<=', $to)
        ->whenSearch(request()->search)
        ->get();
        foreach($revenues as $revenue){
            $revenue_amount += $revenue->revenue_amount ;
        }
        return $revenue_amount ;
    }
}




