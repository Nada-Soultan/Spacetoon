<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Attendance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'attendance';


    protected $fillable =[
        'absence_date','user_id','status','reasons','comments','no_of_hours'
    ];

    public function user()
    {
        return $this->belongsTo(User::class ,'user_id','id');

    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('user_id', 'like', "%$search%");

        });
    }

}
