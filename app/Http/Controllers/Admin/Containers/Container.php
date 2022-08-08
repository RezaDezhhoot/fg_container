<?php

namespace App\Http\Controllers\Admin\Containers;

use App\Enums\LicenseEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Container as ModelsContainer;
use App\Models\ContainerHistory;
use Livewire\WithPagination;

class Container extends BaseComponent
{
    use WithPagination;

    public $product , $tab = 'container' , $table = 'all' , $container_row , $formTitle;

    public $container_code , $container_status , $container_id;

    public $action , $count , $enter_price , $exit_price , $order_id , $description , $product_id;

    protected $queryString = ['product' , 'tab' , 'table'];

    public function mount()
    {
        $this->data['product'] = ['1' => 'test'];
        $this->data['status'] = LicenseEnum::getStatus();
    }


    public function render()
    {
        $this->authorizing('show_container');
        $data = [];
        if ($this->tab == 'container') {
            $container = ModelsContainer::latest('id')->when($this->product,function($q) {
                return $q->where('product_id',$this->product);
            })->search($this->search);

            if ($this->table == 'deleted') {
                $container = $container->onlyTrashed();
            } elseif ($this->table == LicenseEnum::IS_NOT_USED) {
                $container = $container->where('status',LicenseEnum::IS_NOT_USED);
            } elseif ($this->table == LicenseEnum::IS_USED) {
                $container = $container->where('status',LicenseEnum::IS_USED);
            }
            
            $container = $container->paginate($this->per_page);
            $data = ['container'=>$container , 'counter' => $this->counter()];
        } elseif ($this->tab == 'history') {
            $history = ContainerHistory::latest('id')->when($this->product,function($q) {
                return $q->wherehas('exitContainers',function($q) {
                    return $q->where('product_id',$this->product);
                })->orWhereHas('enterContainers',function($q) {
                    return $q->where('product_id',$this->product);
                });
            })->search($this->search);

            if ($this->table == 'enter') {
                $history = $history->where('action',LicenseEnum::ENTER);
            } elseif ($this->table == 'exit') {
                $history = $history->where('action',LicenseEnum::EXIT);
            }

            $history = $history->paginate($this->per_page);

            $data = ['history' => $history];
        }

        return view('admin.containers.container',$data)->extends('admin.includes.admin');
    }

    public function counter()
    {
        return [
            'all' => [
                'count' => ModelsContainer::count(),
                'label' => 'همه'
            ],
            LicenseEnum::IS_USED => [
                'count' => ModelsContainer::where('status',LicenseEnum::IS_USED)->count(),
                'label' => LicenseEnum::getStatus()[LicenseEnum::IS_USED]
            ],
            LicenseEnum::IS_NOT_USED => [
                'count' => ModelsContainer::where('status',LicenseEnum::IS_NOT_USED)->count(),
                'label' => LicenseEnum::getStatus()[LicenseEnum::IS_NOT_USED]
            ],
            'deleted' => [
                'count' => ModelsContainer::onlyTrashed()->count(),
                'label' => 'حذف شده ها'
            ],
            
        ];
    }

    public function deleteFormContianer($id)
    {
        $this->authorizing('edit_container');
        ModelsContainer::destroy($id);
        $this->emitNotify('لایسنس با موفقیت حذف شد');
    }

    public function restoreContainer($id)
    {
        $this->authorizing('edit_container');
        ModelsContainer::onlyTrashed()->find($id)->restore();
        $this->emitNotify('لایسنس با موفقیت بازیابی شد');
    }

    public function editContainer($id)
    {
        $this->authorizing('edit_container');
        $this->container_row = ModelsContainer::findOrFail($id);
        
        $this->container_code = $this->container_row->license;
        $this->container_status = $this->container_row->status;
        $this->container_id = $this->container_row->id;

        $this->formTitle = 'ویرایش کد';

        $this->emitShowModal('edit_container');
    }

    public function storeContainer()
    {
        $this->authorizing('edit_container');
        $this->validate([
            'container_code' => ['required','string','max:250','unique:licenses_container,license,'.($this->container_id ?? 0) ],
            'container_status' => ['required','string','in:'.implode(',',array_keys(LicenseEnum::getStatus()))]
        ],[],[
            'container_code' => 'کد',
            'container_status' => 'وضعیت',
        ]);

        $this->container_row->license = $this->container_code;
        $this->container_row->status = $this->container_status;
        $this->container_row->save();
        $this->emitNotify('لایسنس با موفقیت ویرایش شد');
        $this->emitHideModal('edit_container');
    }


    public function resetForm()
    {
        $this->reset(['action','count','enter_price','exit_price','order_id','description','product_id','formTitle']);
    }


    public function historyFormEnter()
    {
        $this->resetForm();
        $this->emitShowModal('form');   
        $this->formTitle = 'فرم ورود';
        $this->action = LicenseEnum::ENTER;
    }


    public function historyFormExit()
    {
        $this->resetForm();
        $this->emitShowModal('form');
        $this->formTitle = 'فرم خروج';
        $this->action = LicenseEnum::EXIT;
    }
    
    public function storeHistory()
    {
        if ($this->action == LicenseEnum::ENTER) {
            $this->validate([
                'count' => ['required','integer','between:0,999999999999999999'],
                'enter_price' => ['required','between:1,9999999999999.9999999'],
                'description' => ['nullable','string','max:1400'],
                'product_id' => ['required','in:'.(implode(',',array_keys($this->data['product'])))],
            ],[],[
                'count' => 'تعداد',
                'enter_price' => 'قیمت خرید',
                'description' => 'توضیحات',
                'product_id' => 'محصول',
            ]);
        } elseif ($this->action == LicenseEnum::EXIT) {
            $this->validate([
                'count' => ['required','integer','between:0,999999999999999999'],
                'enter_price' => ['required','between:1,9999999999999.9999999'],
                'exit_price' => ['required','between:1,9999999999999.9999999'],
                'description' => ['nullable','string','max:1400'],
                'order_id' => ['required','string','max:250'],
                'product_id' => ['required','in:'.(implode(',',array_keys($this->data['product'])))],
            ],[],[
                'count' => 'تعداد',
                'enter_price' => 'قیمت خرید',
                'exit_price' => 'قیمت فروش',
                'description' => 'توضیحات',
                'order_id' => 'کد سفارش',
                'product_id' => 'محصول',
            ]);
        } else {
            return;
        }

        ContainerHistory::create([
            'action' => $this->action,
            'count' => $this->count,
            'enter_price' => $this->enter_price ?? 0,
            'exit_price' => $this->exit_price ?? 0,
            'order_id' => $this->order_id ?? 0,
            'user_id' => auth()->id(),
            'description' => $this->description,
            'product_title' => $this->data['product'][$this->product_id],
        ]);
        $this->resetForm();
        $this->emitNotify('فرم با موفقیت اضافه شد');
        $this->emitHideModal('form');
    }
}
