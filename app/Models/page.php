<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class page extends Model
{
    protected $fillable = [
        'image',
        'name',
        'is_group',
        'is_active',
        'title',
        'description',
    ];
}
