<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class product extends Model
{
    protected $fillable = [
        'image',
        'title',
        'category_id',
        'description',
        'price',
        'saleprice'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(category::class);
    }
}
