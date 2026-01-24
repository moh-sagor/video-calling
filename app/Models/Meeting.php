<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'host_id',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
}
