<?php

namespace App\Http\Controllers\Admin\Requests;

use App\Http\Controllers\BaseComponent;
use App\Models\Request;
use Livewire\WithPagination;

class IndexRequest extends BaseComponent
{
    use WithPagination;

    public function render()
    {
        $this->authorizing('show_api_requests');
        $requests = Request::latest('id')->search($this->search)->paginate($this->per_page);
        return view('admin.requests.index-request',['requests'=>$requests])->extends('admin.includes.admin');
    }
}
