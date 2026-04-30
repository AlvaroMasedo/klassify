<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'course_id',
        'subject_id',
        'file_url',
        'file_name',
        'file_size',
        'mime_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'resource_id', 'user_id')
            ->withPivot('created_at');
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes', 'resource_id', 'user_id')
            ->withPivot('created_at');
    }
}
