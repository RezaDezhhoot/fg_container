<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

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

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Jalalian::forge($value)->format('%A, %d %B %Y H:i:s')
        );
    }

}
