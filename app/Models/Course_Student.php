<?php

namespace App\Models;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Course_Student extends Model
{
    use HasFactory;
    protected $table = 'course_students';
    protected $fillable = [
        'course_id',
        'user_id',
        'percentage',
        'is_complete',
        'joined_at',
        'completed_at',
        'completed_quizzes',
    ];

    protected $casts = [
        'completed_quizzes' => 'array',
        'is_complete' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
