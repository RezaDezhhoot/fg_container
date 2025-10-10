<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCharge extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'confirm' => 'boolean'
    ];

    public function cart()
    {
        return $this->belongsTo(UnsignedCart::class,'unsigned_cart_id');
    }
}
