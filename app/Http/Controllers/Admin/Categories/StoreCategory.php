<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\BaseComponent;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreCategory extends BaseComponent
{
    public $category;
    public $title , $currency , $price , $header;

    public function mount($action , $id = null)
    {
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE)
        {
            $this->category = Category::query()->findOrFail($id);
            $this->title = $this->category->title;
            $this->price = $this->category->price;
            $this->currency = $this->category->currency_id;
         } elseif ($this->mode == self::CREATE_MODE) $this->header = 'واحد جدید';
        else abort(404);

        $this->data['currency'] = Category::all()->pluck('title','id');
    }

    public function deleteItem()
    {
        $this->category->delete();
        return redirect()->route('categories');
    }

    public function store()
    {
        if ($this->mode == self::UPDATE_MODE) {
            $this->saveInDataBase($this->category);
        } elseif ($this->mode == self::CREATE_MODE) {
            $this->saveInDataBase(new Category());
            $this->resetData();
        }
    }

    private function saveInDataBase(Category $category)
    {
        $this->validate([
            'title' => ['required','string','max:150'],
            'price' => ['required','between:0,999999999999.999'],
            'currency' => ['nullable','exists:currencies,id']
        ],[],[
            'title' => 'عنوان',
            'price' => 'قیمت',
            'currency' => 'واحد پول'
        ]);
        try {
            DB::beginTransaction();
            $category->title = $this->title;
            $category->price = $this->price;
            $category->currency_id  = $this->currency;
            $this->emitNotify('اطلاعات با موفقیت ذخیره شد');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->emitNotify('مشکل در ذخیره اطلاعات','warning');
        }
    }

    public function resetData()
    {
        $this->reset(['title','currency','price']);
    }

    public function render()
    {
        return view('admin.categories.store-category')->extends('admin.includes.admin');
    }
}
