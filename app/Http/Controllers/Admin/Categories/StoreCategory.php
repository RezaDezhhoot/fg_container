<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\BaseComponent;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreCategory extends BaseComponent
{
    public $category;
    public $title , $currency , $price , $header , $is_base = false , $image , $description;

    public function mount($action , $id = null)
    {
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE)
        {
            $this->category = Category::query()->findOrFail($id);
            $this->title = $this->category->title;
            $this->price = $this->category->price;
            $this->is_base = $this->category->is_base;
            $this->image = $this->category->image;
            $this->description = $this->category->description;
            $this->currency = $this->category->currency_id;
         } elseif ($this->mode == self::CREATE_MODE) $this->header = 'واحد جدید';
        else abort(404);

        $this->data['currency'] = Currency::all()->pluck('title','id');
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
            'currency' => ['nullable','exists:currencies,id'],
            'is_base' => ['required','boolean'],
            'description' => [Rule::requiredIf($this->is_base == 1),'max:80'],
            'image' => [Rule::requiredIf($this->is_base == 1),'max:1000'],
        ],[],[
            'title' => 'عنوان',
            'price' => 'قیمت',
            'currency' => 'واحد پول',
            'is_base' => 'دسته بندی پایه',
            'description' => 'توضیحات',
            'image' => 'تصویر'
        ]);
        try {
            DB::beginTransaction();
            $category->title = $this->title;
            $category->price = $this->price;
            $category->currency_id  = $this->currency;
            $category->is_base  = $this->is_base;
            $category->image  = $this->image;
            $category->description  = $this->description;
            $category->save();
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
        $this->reset(['title','currency','price','is_base','image','description']);
    }

    public function render()
    {
        return view('admin.categories.store-category')->extends('admin.includes.admin');
    }
}
