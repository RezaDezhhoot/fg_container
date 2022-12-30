<?php

namespace App\Http\Controllers\Admin\Panels;

use App\Enums\PanelEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Panel;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPanel extends BaseComponent
{
    use WithPagination;

    public $status;

    protected $queryString = ['status'];

    public function mount()
    {
        $this->data['status'] = PanelEnum::getStatus();
    }

    public function render()
    {
        $items = Panel::query()->latest()->when($this->status,function ($q){
            return $q->where('status',$this->status);
        })->search($this->search)->paginate($this->per_page);

        return view('admin.panels.index-panel',['items'=>$items])->extends('admin.includes.admin');
    }
}
