<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_Content extends Model
{
    protected $table = 'course_contents'; 

    protected $fillable = [
        'course_id',
        'course_level_id',
        'title',
        'content_type',
        'session',
        'content',
    ];

    protected $casts = [
        'session' => 'integer',
    ];

    public function courseLevel()
    {
        return $this->belongsTo(Course_Level::class, 'course_level_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Course_Quiz::class, 'course_content_id', 'id');
    }
}