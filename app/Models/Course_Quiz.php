<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course_Content;
use Illuminate\Database\Eloquent\Model;

class Course_Quiz extends Model
{
    use HasFactory;
    
    protected $table = 'course_quizzes';
    protected $fillable = [
        'course_content_id',
        'name',
        'questions', 
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public function courseContent()
    {
        return $this->belongsTo(Course_Content::class, 'course_content_id');
    }
}
