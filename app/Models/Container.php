<?php

namespace App\Models;

use App\Enums\LicenseEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

/**
 * @method static isNotUsed($product_id)
 */
class Container extends Model
{

    protected $table = 'licenses_container';

    use HasFactory , Searchable , SoftDeletes;
    protected $searchAbleColumns = ['form_enter_id','form_exit_id'];

    protected $guarded = ['id'];

    public function exit_form()
    {
        return $this->belongsTo(ContainerHistory::class,'form_exit_id');
    }

    public function enter_form()
    {
        return $this->belongsTo(ContainerHistory::class,'form_enter_id');
    }

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status , array_keys(LicenseEnum::getStatus()) ) ? LicenseEnum::getStatus()[$this->status] : 'نامشخص'
        );
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Jalalian::forge($value)->format('%A, %d %B %Y')
        );
    }

    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Jalalian::forge($value)->format('%A, %d %B %Y')
        );
    }

    public function scopeIsNotUsed($query, $product_id)
    {
        return $query->where('product_id',$product_id)->where('status',LicenseEnum::IS_NOT_USED);
    }
}
