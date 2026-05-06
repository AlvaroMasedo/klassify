<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'recipient_id',
        'actor_id',
        'resource_id',
        'comment_id',
        'type',
        'read_at',
        'created_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
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