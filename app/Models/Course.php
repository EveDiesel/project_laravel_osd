<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_url',
        'is_active',
        'course_type',
        'is_displayed',
        'created_by',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_displayed' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(Entity::class, 'created_by');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Course_Content::class, 'course_id');
    }
}