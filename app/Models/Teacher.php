<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
      'user_id'  ,'salary','extra_courses','fees_of_courses'
    ];

    public function user() {

        return $this->belongsTo(User::class,'user_id','id');

    }

    public function classes(){

        return $this->hasMany(ClassModel::class);
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('user_id', 'like', "%$search%");

        });
    }


}
