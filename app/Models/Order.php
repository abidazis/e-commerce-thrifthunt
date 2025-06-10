<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Pastikan kolom-kolom baru/lama ada di fillable
    protected $fillable = [
        'user_id',
        'order_number', // Tambahkan ini
        'total_amount', // Tambahkan ini
        'status',
        'shipping_address',
        'shipping_method',
        'payment_method',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi BARU ke OrderItems
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Hapus relasi product() jika sebelumnya ada relasi hasOne/belongsTo Product
    // Karena sekarang produk ada di order_items
    // public function product() { /* hapus ini jika ini adalah relasi langsung ke satu produk */ }
}