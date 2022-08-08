<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * @property mixed name
 * @method static insert(array $data)
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    protected $fillable = ['name','guard_name'];
    protected $attributes = [
        'guard_name' => 'web',
    ];

    public function lang()
    {
        return [
            'show' => 'نمایش',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
            'dashboard' => 'داشبورد',
            'users' => 'کاربران',
            'roles' => 'نقش ها',
            'api_requests' => 'درخواست ها',
            'container' => 'مخزن لایسن ها',
        ];
    }

    public function getLabelAttribute()
    {
        $names = explode('_',$this->name);
        if (in_array(str_replace($names[0].'_','',$this->name),array_keys($this->lang()))){
            $action = $this->lang()[$names[0]];
            $model = $this->lang()[str_replace($names[0].'_','',$this->name)];
            return $action.' '.$model;
        }
        return  $this->name;
    }

}
