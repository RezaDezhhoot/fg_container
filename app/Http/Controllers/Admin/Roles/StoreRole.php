<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\BaseComponent;
use App\Models\Permission;
use App\Models\Role;

class StoreRole extends BaseComponent
{
    public object $role ;
    public $permission , $name  , $header  , $permissionSelected = [];

    public function mount($action , $id = null)
    {
        $this->authorizing('show_roles');
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE)
        {
            $this->role = Role::filter()->findOrFail($id);
            $this->header = $this->role->name;
            $this->name = $this->role->name;
            $this->permissionSelected = $this->role->permissions()->pluck('name')->toArray();
        } elseif ($this->mode == self::CREATE_MODE) $this->header = 'نقش جدید';
        else abort(404);
        $this->permission = Permission::all();
    }

    public function store()
    {
        $this->authorizing('edit_roles');
        if ($this->mode == self::UPDATE_MODE)
            $this->saveInDateBase($this->role);
        elseif ($this->mode == self::CREATE_MODE) {
            $this->saveInDateBase(new Role());
            $this->reset(['name','permissionSelected']);
        }
    }

    public function saveInDateBase($model)
    {
        $this->validate(
            [
                'name' => ['required', 'string','max:250'],
                'permissionSelected' => ['required', 'array'],
                'permissionSelected.*' => ['required', 'exists:permissions,name'],
            ] , [] , [
                'name' => 'عنوان',
                'permissionSelected' => 'دسترسی ها',
                'permissionSelected.*' => 'دسترسی ها',
            ]
        );
        $model->name = $this->name;
        $model->save();
        $model->syncPermissions($this->permissionSelected);
        $this->emitNotify('اطلاعات با موفقیت ثبت شد');
    }

    public function deleteItem()
    {
        $this->authorizing('delete_roles');
        Role::destroy($this->role->id);
        return redirect()->route('role');
    }

    public function render()
    {
        return view('admin.roles.store-role')->extends('admin.includes.admin');
    }
}
