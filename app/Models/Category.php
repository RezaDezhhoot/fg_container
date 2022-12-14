<?php

namespace App\Models;

use App\Traits\Admin\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $currency
 * @property mixed $price
 * @property mixed $currency_id
 * @property mixed $title
 * @property bool|mixed $is_base
 * @property mixed $image
 * @property mixed $description
 */
class Category extends Model
{
    use HasFactory , Searchable;

    protected array $searchAbleColumns = ['title'];

    protected $casts = [
        'is_base' => 'boolean'
    ];

    protected $guarded = ['id'];


    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function amount():Attribute
    {
        return Attribute::make(
            get: fn() => is_null($this->currency) ? $this->price : $this->price * $this->currency->amount
        );
    }

    public function level(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_base ? 'پایه' : 'عادی'
        );
    }
}
