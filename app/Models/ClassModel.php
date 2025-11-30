<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ClassModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'classes';


    protected $fillable = [

        'class_name','class_stage','user_id','students_no'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');

    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id', 'id');
    }
    

    

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('class_name', 'like', "%$search%");

        });
    }

}
