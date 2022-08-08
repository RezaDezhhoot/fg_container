<?php

namespace App\Http\Controllers\Admin\Containers;

use Livewire\Component;

class Container extends Component
{
    public function render()
    {
       
        return view('admin.containers.container')->extends('admin.includes.admin');
    }
}
