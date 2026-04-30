<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'reporter_id',
        'resource_id',
        'comment_id',
        'reason',
        'status',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}