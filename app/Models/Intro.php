<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'photo',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
