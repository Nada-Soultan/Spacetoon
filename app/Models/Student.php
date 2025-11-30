<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'age', 'date_of_join', 'date_of_birth', 'fees_of_uniform', 'fees_of_book', 'image_id', 'class_id'

    ];


    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }



    public function image()
    {

        return $this->hasMany(StudentImage::class, 'id', 'image_id');
    }








    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
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
}
