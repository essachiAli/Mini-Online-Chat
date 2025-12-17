<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'content',
    ];
    protected $consts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
