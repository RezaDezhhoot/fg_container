<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\BaseComponent;

class Profile extends BaseComponent
{
    public $user, $header, $role, $name , $email, $phone, $password ;

    public function mount()
    {
        $this->user = auth()->user();
        $this->header = $this->user->name;
        $this->name = $this->user->name;
        $this->phone = $this->user->phone;
        $this->email = $this->user->email;
    }

    public function render()
    {
        return view('admin.profile.profile')->extends('admin.includes.admin');
    }
    public function store()
    {
        $fields = [
            'name' => ['required', 'string','max:150'],
            'phone' => ['required','size:11' , 'unique:users,phone,'. ($this->user->id ?? 0)],
            'email' => ['required','email','max:255' , 'unique:users,email,'. ($this->user->id ?? 0)],
        ];
        $messages = [
            'name' => 'نام ',
            'phone' => 'شماره همراه',
            'email' => 'ایمیل',
        ];
        if (isset($this->password) && !empty($this->password))
        {
            $fields['password'] = ['required','min:5','regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9]).*$/'];
            $messages['password'] = 'گذرواژه';
        }
        $this->validate($fields,[],$messages);


        $this->user->name = $this->name;
        $this->user->phone = $this->phone;
        $this->user->email = $this->email;
        if (isset($this->password))
            $this->user->password = $this->password;


        $this->user->save();
        $this->emitNotify('اطلاعات با موفقیت ثبت شد');
    }
}
