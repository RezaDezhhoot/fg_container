<?php

namespace App\Http\Controllers\Admin\Currencies;

use App\Http\Controllers\BaseComponent;
use App\Models\Currency;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCurrency extends BaseComponent
{
    use WithPagination;

    public function render()
    {
        $items = Currency::query()->latest()->search($this->search)->paginate($this->per_page);
        return view('admin.currencies.index-currency',['items'=>$items])->extends('admin.includes.admin');
    }
}
