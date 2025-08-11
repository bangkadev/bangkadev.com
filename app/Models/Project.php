<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'project_category_id',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projectCategory()
    {
        return $this->belongsTo(ProjectCategory::class);
    }
}
