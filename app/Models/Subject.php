<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function subjects()
{
    return $this->hasMany(Subject::class);
}
}
