<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Skill extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'icon'
    ];

    public static function boot()
    {
        parent::boot();

        static ::creating(function ($model) {
            if(Auth::user()->role === 'pengguna') {
                $model->user_id = Auth::user()->id;
            }
        });

        static ::updating(function ($model) {
            if(Auth::user()->role === 'pengguna') {
                $model->user_id = Auth::user()->id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
