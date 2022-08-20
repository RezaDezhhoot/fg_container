<?php

namespace App\Models;

use App\Traits\Admin\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\LicenseEnum;

/**
 * @method static create(array $array)
 * @method static find($id)
 * @method static latest(string $string)
 * @property mixed $action
 */
class ContainerHistory extends Model
{

    protected $table = 'licenses_container_history';

    use HasFactory , Searchable;


    protected array $searchAbleColumns = ['id','order_id'];

    protected $guarded = ['id'];

    public function exitContainers(): HasMany
    {
        return $this->hasMany(Container::class,'form_exit_id');
    }

    public function enterContainers(): HasMany
    {
        return $this->hasMany(Container::class,'form_enter_id');
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Jalalian::forge($value)->format('%A, %d %B %Y H:i:s')
        );
    }

    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Jalalian::forge($value)->format('%A, %d %B %Y H:i:s')
        );
    }

    public function actionLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->action , array_keys(LicenseEnum::getActions()) ) ? LicenseEnum::getActions()[$this->action] : 'نامشخص'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
