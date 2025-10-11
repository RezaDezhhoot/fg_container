<?php

namespace App\Http\Controllers\Admin\Carts;

use App\Http\Controllers\BaseComponent;
use App\Models\Cart;
use App\Models\UnsignedCart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreCart extends BaseComponent
{
    public $cart , $header;

    public $cart_id;

    public $cart_number , $cart_cvv2 , $image , $expire , $category , $status , $type;
    public $name , $used;

    public function mount($action , $id =null)
    {
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE)
        {
            $this->cart = UnsignedCart::query()->with('cart')->findOrFail($id);
            $this->header = $this->cart->masked_pan;
            $this->cart_number = $this->cart->masked_pan;
            $this->cart_id = $this->cart->cart_id;
            $this->name = $this->cart->name;
            $this->used = $this->cart->used;
            $this->cart_cvv2 = $this->cart?->cart->cart_cvv2;
            $this->expire = $this->cart?->cart->expire;
        } elseif ($this->mode == self::CREATE_MODE) {
            $this->header = 'کارت جدید';
        } else abort(404);
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
            $this->saveInDatBase(new UnsignedCart());
            $this->resetData();
        }
    }

    private function saveInDatBase(UnsignedCart $cart)
    {
        $this->validate([
            'cart_number' => ['required','string','max:20','unique:carts,id,'.($this->cart->id??0)],
            'cart_cvv2' => ['required','string','max:10'],
            'cart_id' => ['required','integer'],
            'expire' => ['required','string','max:20'],
            'name' => ['required','string','max:150'],
            'used' => ['boolean']
        ],[],[
            'cart_number' => 'شماره کارت',
            'cart_cvv2' => 'cvv2',
            'cart_id' => 'ای دی کارت',
            'expire' => 'تاریخ انقضا',
            'name' => 'نام کارت',
            'used' => 'استفاده شده'
        ]);

        try {
            DB::beginTransaction();
            $cart->masked_pan = $this->cart_number;
            $cart->name = $this->name;
            $cart->used = $this->used;
            $cart->cart_id = $this->cart_id;
            $cart->save();

            $cart2 = $this->cart?->cart ?: new Cart();
            $cart2->cart_number  = $this->cart_number;
            $cart2->cart_cvv2 = $this->cart_cvv2;
            $cart2->expire = $this->expire;
            $cart2->is_new = true;
            $cart2->save();

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
        $this->reset(['cart_number','cart_cvv2','image','expire','category','status','type','cart_id','name']);
    }

    public function render()
    {
        return view('admin.carts.store-cart')->extends('admin.includes.admin');
    }
}
