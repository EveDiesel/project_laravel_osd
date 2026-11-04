<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'activity_type',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
