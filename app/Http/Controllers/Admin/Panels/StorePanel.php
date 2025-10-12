<?php

namespace App\Http\Controllers\Admin\Panels;

use App\Enums\CartEnum;
use App\Enums\PanelEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Cart;
use App\Models\Panel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class StorePanel extends BaseComponent
{
    use WithPagination;

    public $panel , $username , $password , $name , $image , $status , $header , $carts = [] , $searches = [] , $phone;

    public function mount($action , $id =null)
    {
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE) {
            $this->panel = Panel::query()->findOrFail($id);
            $this->header = $this->panel->name;
            $this->name = $this->panel->name;
            $this->username = $this->panel->username;
            $this->phone = $this->panel->phone;
            $this->image = $this->panel->image;
            $this->status = $this->panel->status;
            $this->carts = $this->panel->carts()->latest()->select('id','status','cart_number')->take(50)->get();
            $this->carts->each(function ($model){
               $model->setAppends(['status_label']);
            });
            $this->carts = $this->carts->toArray();
        } elseif ($this->mode == self::CREATE_MODE) {
            $this->header = 'پتل چدید';
        } else abort(404);

        $this->data['status'] = PanelEnum::getStatus();
    }

    public function deleteItem()
    {
        $this->panel->delete();
        return redirect()->route('panels');
    }

    public function store()
    {
        if ($this->mode == self::UPDATE_MODE) {
            $this->saveInDataBase($this->panel);
        } elseif ($this->mode == self::CREATE_MODE) {
            $this->saveInDataBase(new Panel());
            $this->resetData();
        }
    }

    private function saveInDataBase(Panel $panel)
    {
        $this->validate([
            'username' => ['required','string','max:120','unique:panels,username,'.($this->panel->id ?? 0)],
            'phone' => ['required','string','max:120','unique:panels,phone,'.($this->panel->id ?? 0)],
            'password' => [Rule::requiredIf(fn( ) => $this->mode ==  self::CREATE_MODE || $panel->isDirty('password') ),'max:180'],
            'name' => ['required','string','max:100'],
            'image' => ['nullable','string','max:1000'],
            'status' => ['required','in:'.implode(',',array_keys($this->data['status']))]
        ],[],[
            'username' => 'نام کاربری',
            'phone' => 'شماره همراه',
            'password' => 'رمزعبور',
            'name' => 'نام',
            'image' => 'تصویر',
            'status' => 'وضعیت'
        ]);

        try {
            DB::beginTransaction();
            $panel->username = $this->username;
            $panel->phone = $this->phone;
            if (!empty($this->password) || $this->mode == self::CREATE_MODE)
                $panel->password = $this->password;
            $panel->name = $this->name;
            $panel->image = $this->image;
            $panel->status = $this->status;
            $panel->save();

            foreach ($this->carts as $cart) {
                if ($cart['id'] === 0) {
                    $cart = Cart::query()->where('cart_number',$cart['cart_number'])->with('cart')->first();
                    if (!is_null($cart)) {
                        $cart->update([
                            'panel_id' => $panel->id,
                            'status' => CartEnum::USED,
                            'used' => true,
                            'name' => $cart->cart->name
                        ]);
                    }
                }
            }
            $this->emitNotify('اطلاعات با موقیت ذخیره شد');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
//            $this->emitNotify('مشکل در حین انجام عملیات','warning');
            $this->emitNotify($e->getMessage(),'warning');
        }
    }

    public function resetData()
    {
        $this->reset(['username','password','status','name','image','carts','phone']);
    }

    public function searchCart()
    {
        $this->searches = Cart::query()
            ->whereHas('cart')
            ->latest()
//            ->ready()
            ->where('cart_number','like','%'.$this->search.'%')
            ->whereNotIn('cart_number',array_column($this->carts,'cart_number'))
            ->take(5)
            ->get();
    }

    public function addCart()
    {
        $this->emitShowModal('carts');
    }

    public function saveCart($number)
    {
        $this->carts[] = [
            'id' => 0,
            'status_label' => CartEnum::getStatus()[CartEnum::READY],
            'cart_number' => $number
        ];
        $this->reset(['searches','search']);
        $this->emitHideModal('carts');
    }

    public function removeCart($id , $key)
    {
        if ($id != 0) Cart::query()->where('id',$id)->update(['panel_id'=>null]);
        unset($this->carts[$key]);
    }

    public function render()
    {
        return view('admin.panels.store-panel')->extends('admin.includes.admin');
    }
}
