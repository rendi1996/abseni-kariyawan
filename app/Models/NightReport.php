<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NightReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report',
        'photo_path',
        'latitude',
        'longitude',
        'address',
        'reported_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
