<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\BaseComponent;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCategory extends BaseComponent
{
    use WithPagination;

    public function render()
    {
        $items = Category::query()->search($this->search)->paginate($this->per_page);

        return view('admin.categories.index-category',['items'=>$items])->extends('admin.includes.admin');
    }
}
