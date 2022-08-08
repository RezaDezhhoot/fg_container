<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\BaseComponent;
use App\Models\Role;
use App\Models\User;

class StoreUser extends BaseComponent
{
    public $user  ,$name, $header , $userRole = [] , $password  , $email   , $phone ;

    public function mount($action , $id = null)
    {
        $this->authorizing('show_users');
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE)
        {
            $this->header = 'کاربر شماره '.$id;
            $this->user = User::findOrFail($id);
            $this->name = $this->user->name;
            $this->phone = $this->user->phone;
            $this->email = $this->user->email;     
            $this->userRole = $this->user->roles()->pluck('name','id')->toArray();
        } elseif ($this->mode == self::CREATE_MODE)
            $this->header = 'کاربر جدید';
        else abort(404);

        $this->data['role'] = Role::filter('admin')->get();
        
    }

    public function store()
    {
        $this->authorizing('edit_users');
        if ($this->mode == self::UPDATE_MODE)
            $this->saveInDataBase($this->user);
        else {
            $this->saveInDataBase(new User());
            $this->reset([
                'name','phone','email'
            ]);
        }
    }

    public function saveInDataBase($model)
    {

        $fields = [
            'name' => ['required', 'string','max:65'],
            'phone' => ['required', 'size:11' , 'unique:users,phone,'. ($this->user->id ?? 0)],
            'email' => ['required','email','max:200','unique:users,email,'. ($this->user->id ?? 0)], 
        ];
        $messages = [
            'name' => 'نام ',
            'phone' => 'شماره همراه',
            'email' => 'ایمیل',
        ];
        

        if ($this->mode == self::CREATE_MODE)
        {
            $fields['password'] = ['required','min:5','regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9]).*$/'];
            $messages['password'] = 'گذرواژه';
        }

        $this->validate($fields,[],$messages);

        $model->name = $this->name;
        $model->phone = $this->phone;
        $model->email = $this->email;
  
        if ($this->mode == self::CREATE_MODE)
            $model->password = $this->password;

        $model->save();

       
        $model->syncRoles($this->userRole);

        $this->emitNotify('اطلاعات با موفقیت ثبت شد');
    }


    public function render()
    {
        return view('admin.users.store-user')->extends('admin.includes.admin');
    }
}
