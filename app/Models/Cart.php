<?php

namespace App\Models;

use App\Enums\CartEnum;
use App\Traits\Admin\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $status
 * @property mixed $cart_number
 * @property mixed $cart_cvv2
 * @property mixed $image
 * @property mixed $expire
 * @property mixed $category_id
 * @property mixed $type
 */
class Cart extends Model
{
    use HasFactory , Searchable;

    protected $searchAbleColumns = ['cart_number','cvv2'];

    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function statusLabel():Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status,array_keys(CartEnum::getStatus())) ? CartEnum::getStatus()[$this->status] : 'نامشخص'
        );
    }

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function scopeReady($q)
    {
        return $q->where('status',CartEnum::READY);
    }

    public function typeLabel():Attribute
    {
        return Attribute::make(
            get: fn() => CartEnum::getType()[$this->type]
        );
    }
}
