<?php

namespace App\Http\Controllers\Admin\Carts;

use App\Enums\CartEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Cart;
use App\Models\UnsignedCart;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCart extends BaseComponent
{
    use WithPagination;

    public function mount()
    {

    }

    public function render()
    {
        $items = UnsignedCart::query()->with('cart')->latest()->search($this->search)->paginate($this->per_page);
        return view('admin.carts.index-cart',['items'=>$items])->extends('admin.includes.admin');
    }
}
