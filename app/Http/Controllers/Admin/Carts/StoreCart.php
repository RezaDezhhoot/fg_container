<?php

namespace App\Http\Controllers\Admin\Carts;

use App\Enums\CartEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class StoreCart extends BaseComponent
{
    public $cart , $header;

    public $cart_number , $cart_cvv2 , $image , $expire , $category , $status;

    public function mount($action , $id =null)
    {
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE)
        {
            $this->cart = Cart::query()->findOrFail($id);
            $this->header = $this->cart->cart_number;
            $this->cart_number = $this->cart->cart_number;
            $this->cart_cvv2 = $this->cart->cart_cvv2;
            $this->image = $this->cart->image;
            $this->expire = $this->cart->expire;
            $this->category = $this->cart->category_id;
            $this->status = $this->cart->status;
        } elseif ($this->mode == self::CREATE_MODE) {
            $this->header = 'کارت جدید';
        } else abort(404);

        $this->data['status'] = CartEnum::getStatus();
        $this->data['category'] = Category::all()->pluck('title','id');

    }

    public function deleteItem()
    {
        $this->cart->delete();
        return redirect()->route('carts');
    }

    public function store()
    {
        if ($this->mode == self::UPDATE_MODE) {
            $this->saveInDatBase($this->cart);
        } elseif ($this->mode == self::CREATE_MODE) {
            $this->saveInDatBase(new Cart());
            $this->resetData();
        }
    }

    private function saveInDatBase(Cart $cart)
    {
        $this->validate([
            'cart_number' => ['required','string','max:20','unique:carts,id,'.($this->cart->id??0)],
            'cart_cvv2' => ['required','string','max:10'],
            'image' => ['required','string','max:1000'],
            'expire' => ['required','string','max:20'],
            'category' => ['required','exists:categories,id'],
            'status' => ['required','in:'.implode(',',array_keys($this->data['status']))]
        ],[],[
            'cart_number' => 'شماره کارت',
            'cart_cvv2' => 'cvv2',
            'image' => 'تصویر',
            'expire' => 'تاریخ انقضا',
            'category' => 'دسته بندی',
            'status' => 'وضعیت'
        ]);

        try {
            DB::beginTransaction();
            $cart->cart_number = $this->cart_number;
            $cart->cart_cvv2 = $this->cart_cvv2;
            $cart->image = $this->image;
            $cart->expire = $this->expire;
            $cart->category_id = $this->category;
            $cart->status = $this->status;
            $cart->save();
            $this->emitNotify('اطلاعات با موفقیت ذخیره شد');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->emitNotify('مشکل در حین انجام عملیات','warning');
        }
    }

    public function resetData()
    {
        $this->reset(['cart_number','cart_cvv2','image','expire','category','status']);
    }

    public function render()
    {
        return view('admin.carts.store-cart')->extends('admin.includes.admin');
    }
}
