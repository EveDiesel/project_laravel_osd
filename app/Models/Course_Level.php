<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course_Level extends Model
{

    protected $table = 'course_levels';

    protected $fillable = [
        'course_id',
        'name',
        'level',
        'meeting_count',
        'duration',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'meeting_count' => 'integer',
        'duration' => 'integer',
        'order_index' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relasi ke Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Course_Content::class, 'course_level_id');
    }
}