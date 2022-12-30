<?php

namespace App\Http\Controllers\Admin\Currencies;

use App\Http\Controllers\BaseComponent;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreCurrency extends BaseComponent
{
    public $currency;

    public $title , $symbol , $amount , $header;

    public function mount($action , $id = null)
    {
        $this->set_mode($action);
        if ($this->mode == self::UPDATE_MODE)
        {
            $this->currency = Currency::query()->findOrFail($id);
            $this->header = $this->currency->title;
            $this->title = $this->currency->title;
            $this->symbol = $this->currency->symbol;
            $this->amount = $this->currency->amount;
        } elseif ($this->mode == self::CREATE_MODE) $this->header = 'واحد جدید';
        else abort(404);
    }

    public function deleteItem()
    {
        $this->currency->delete();
        return redirect()->route('currencies');
    }

    public function store()
    {
        if ($this->mode == self::UPDATE_MODE) {
            $this->saveInDataBase($this->currency);
        } elseif($this->mode == self::CREATE_MODE) {
            $this->saveInDataBase(new Currency());
            $this->resetData();
        }
    }

    private function saveInDataBase(Currency $currency)
    {
        $this->validate([
            'title' => ['string','max:70','required'],
            'symbol' => ['required','max:5','string'],
            'amount' => ['required','numeric','between:0,99999999.99999']
        ],[],[
            'title' => 'عنوان',
            'symbol' => 'نماد',
            'amount' => 'قیمت'
        ]);
        try {
            DB::beginTransaction();
            $currency->title = $this->title;
            $currency->symbol = $this->symbol;
            $currency->amount = $this->amount;
            $currency->save();
            $this->emitNotify('اطلاعات با موفقیت ذخیره شد');
            DB::commit();
        } catch (\Exception $exception)
        {
            DB::rollBack();
            Log::error($exception->getMessage());
            $this->emitNotify('اطلاعات با موفقیت ذخیره نشد','warning');
        }
    }

    public function resetData()
    {
        $this->reset(['title','symbol','amount']);
    }

    public function render()
    {
        return view('admin.currencies.store-currency')->extends('admin.includes.admin');
    }
}
