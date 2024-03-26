<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class card extends Model
{
    use HasFactory;
    protected $table = 'card';
    protected $fillable = [
        'name',
        'company',
        'title',
        'user_id',
    ];
}
