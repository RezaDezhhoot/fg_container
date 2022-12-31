<?php

namespace App\Http\Controllers\Admin\Carts;

use App\Enums\CartEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCart extends BaseComponent
{
    use WithPagination;

    public $status , $type;

    protected $queryString = ['status' , 'type'];

    public function mount()
    {
        $this->data['status'] = CartEnum::getStatus();
        $this->data['type'] = CartEnum::getType();
    }

    public function render()
    {
        $items = Cart::query()->latest()->when($this->status,function ($q) {
            return $q->where('status',$this->status);
        })->when($this->type,function ($q){
            return $q->where('type',$this->type);
        })->search($this->search)->paginate($this->per_page);
        return view('admin.carts.index-cart',['items'=>$items])->extends('admin.includes.admin');
    }
}
