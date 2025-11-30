<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revenue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[

        'revenue_type','revenue_amount','notes','user_id',


    ];

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('revenue_type', 'like', "%$search%");

        });
    }

    public function user() {

        return $this->belongsTo(User::class ,'user_id','id');

    }
}
