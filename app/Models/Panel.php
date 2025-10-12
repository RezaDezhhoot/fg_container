<?php

namespace App\Models;

use App\Enums\PanelEnum;
use App\Traits\Admin\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

/**
 * @property mixed $status
 * @property mixed $username
 * @property mixed $password
 * @property mixed $name
 * @property mixed $image
 * @property mixed $phone
 */
class Panel extends Model
{
    use HasFactory , Searchable;
    protected array $searchAbleColumns = ['username','name','phone'];

    public function statusLabel():Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status,array_keys(PanelEnum::getStatus())) ? PanelEnum::getStatus()[$this->status] : 'نامشخص'
        );
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function password():Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value)
        );
    }

    public function charges(): HasMany
    {
        return $this->hasMany(CartCharge::class,'panel_id');
    }
}
