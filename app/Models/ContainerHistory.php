<?php

namespace App\Models;

use App\Traits\Admin\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\LicenseEnum;

class ContainerHistory extends Model
{

    protected $table = 'licenses_container_history';

    use HasFactory , Searchable;

    
    protected $searchAbleColumns = ['id'];

    protected $guarded = ['id'];

    public function exitContainers()
    {
        return $this->hasMany(Container::class,'form_exit_id');
    }

    public function enterContainers()
    {
        return $this->hasMany(Container::class,'form_enter_id');
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

    public function actionLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->action , array_keys(LicenseEnum::getActions()) ) ? LicenseEnum::getActions()[$this->action] : 'نامشخص'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
