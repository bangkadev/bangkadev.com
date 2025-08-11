<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = [
        'user_id',
        'company',
        'logo',
        'position',
        'start_year',
        'end_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
