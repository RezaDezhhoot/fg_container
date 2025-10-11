<?php

namespace App\Models;

use App\Traits\Admin\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnsignedCart extends Model
{
    use HasFactory , Searchable;

    protected $searchAbleColumns = ['masked_pan','cart_id'];

    protected $guarded = ['id'];

    protected $casts = [
        'used' => 'boolean'
    ];

    public function cart()
    {
        return $this->hasOne(Cart::class,'cart_number','masked_pan');
    }
}
