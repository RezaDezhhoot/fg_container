<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\BaseComponent;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IndexUser extends BaseComponent
{
    use WithPagination ;

    public function render()
    {
        $this->authorizing('show_users');
        $users = User::latest('id')->search($this->search)->paginate($this->per_page);
        return view('admin.users.index-user',['users'=>$users])->extends('admin.includes.admin');
    }
}
