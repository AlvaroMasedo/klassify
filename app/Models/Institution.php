<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'city'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
