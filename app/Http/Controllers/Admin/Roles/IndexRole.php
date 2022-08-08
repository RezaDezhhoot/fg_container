<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\BaseComponent;
use App\Models\Role;
use Livewire\WithPagination;

class IndexRole extends BaseComponent
{
    use WithPagination ;
    public ?string $placeholder = 'عنوان';
    public function render()
    {
        $this->authorizing('show_roles');
        $roles = Role::latest('id')->filter()->search($this->search)->paginate($this->per_page);
        return view('admin.roles.index-role',['roles' => $roles])->extends('admin.includes.admin');
    }
}
