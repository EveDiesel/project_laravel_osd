<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'birthdate',
        'avatar_url',
        'city',
        'gender',
        'is_company',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
