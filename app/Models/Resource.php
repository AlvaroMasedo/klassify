<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}