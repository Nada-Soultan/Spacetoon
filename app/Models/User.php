<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions;
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name', 'email', 'password',  'phone', 'gender', 'profile',  'status', 'lang','phone_verified_at'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


   


    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('phone', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('id', 'like', "$search");
        });
    }


    public function scopeWhenStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {
            if ($status == 'active' || $status == 'inactive') {
                return $status == 'active' ? $q->whereNotNull('phone_verified_at') : $q->whereNull('phone_verified_at');
            } else {
                return $q->where('status', 'like', $status);
            }
        });
    }

    public function scopeWhenRole($query, $role_id)
    {
        return $query->when($role_id, function ($q) use ($role_id) {
            return $this->scopeWhereRole($q, $role_id);
        });
    }

    public function scopeWhereRole($query, $role_name)
    {
        return $query->whereHas('roles', function ($q) use ($role_name) {
            return $q->whereIn('name', (array)$role_name)
                ->orWhereIn('id', (array)$role_name);
        });
    }
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }
   protected static function booted()
{
    static::deleting(function ($user) {

        // If this is a force delete, delete child permanently
        if ($user->isForceDeleting()) {
            if ($user->student) {
                $user->student->forceDelete();
            }
            if ($user->teacher) {
                $user->teacher->forceDelete();
            }
        }

        // If this is soft delete, soft delete children
        else {
            if ($user->student) {
                $user->student->delete();
            }
            if ($user->teacher) {
                $user->teacher->delete();
            }
        }
    });
}

}
