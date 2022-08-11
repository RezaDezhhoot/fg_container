<?php

namespace App\Http\Controllers\Admin\Containers;

use App\Enums\LicenseEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Container as ModelsContainer;
use App\Models\ContainerHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class Container extends BaseComponent
{
    use WithPagination;

    public $product , $tab = 'container' , $table = 'all' , $container_row , $formTitle;

    public $container_code , $container_status , $container_id;

    public $action , $count , $enter_price , $exit_price , $order_id , $description , $product_id , $codes = [];

    public $history_row , $historyAction , $search_result = [] , $searchProduct , $maxCount = 0;

    protected $queryString = ['product' , 'tab' , 'table'];

    public function mount()
    {
        $response  = Http::accept('application/json')
            ->get('https://farsgamer.com/api/products');
        $products = collect($response->json()['data']['products']['records'])
            ->pluck('title','id')
            ->toArray();
        $this->data['product'] = $products;
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
                'count' => ModelsContainer::when($this->product,function($q) {
                    return $q->where('product_id',$this->product);
                })->count(),
                'label' => 'همه'
            ],
            LicenseEnum::IS_USED => [
                'count' => ModelsContainer::when($this->product,function($q) {
                    return $q->where('product_id',$this->product);
                })->where('status',LicenseEnum::IS_USED)->count(),
                'label' => LicenseEnum::getStatus()[LicenseEnum::IS_USED]
            ],
            LicenseEnum::IS_NOT_USED => [
                'count' => ModelsContainer::when($this->product,function($q) {
                    return $q->where('product_id',$this->product);
                })->where('status',LicenseEnum::IS_NOT_USED)->count(),
                'label' => LicenseEnum::getStatus()[LicenseEnum::IS_NOT_USED]
            ],
            'deleted' => [
                'count' => ModelsContainer::when($this->product,function($q) {
                    return $q->where('product_id',$this->product);
                })->onlyTrashed()->count(),
                'label' => 'حذف شده ها'
            ],

        ];
    }

    public function deleteFormContainer($id)
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
        $this->reset(['action','maxCount','search_result','searchProduct','count','enter_price','exit_price','order_id','description','product_id','formTitle','codes','history_row']);
    }

    public function historyFormEnter($id)
    {
        $this->resetForm();
        $this->formTitle = 'فرم ورود';
        $this->action = LicenseEnum::ENTER;
        if ($id != 0) {
            $this->historyAction = 'edit';
            $this->history_row = ContainerHistory::with('enterContainers')->find($id);
            $this->codes = $this->history_row->enterContainers()->select('id','license')->get()->toArray();
            $this->enter_price = $this->history_row->enter_price;
            $this->description = $this->history_row->description;
            $this->product_id = $this->history_row->product_id;
            $this->count = $this->history_row->count;
        } else {
            $this->historyAction = 'new';
        }
        $this->emitShowModal('form');
    }

    public function historyFormExit($id)
    {
        $this->resetForm();
        $this->emitShowModal('form');
        $this->formTitle = 'فرم خروج';
        $this->action = LicenseEnum::EXIT;
        if ($id != 0) {
            $this->historyAction = 'edit';
            $this->history_row = ContainerHistory::with('enterContainers')->find($id);
            $this->codes = $this->history_row->exitContainers()->select('id','license')->get()->toArray();
            $this->enter_price = $this->history_row->enter_price;
            $this->exit_price = $this->history_row->exit_price;
            $this->order_id = $this->history_row->order_id;
            $this->description = $this->history_row->description;
            $this->product_id = $this->history_row->product_id;
            $this->count = $this->history_row->count;
        } else {
            $this->historyAction = 'new';
        }
        $this->emitShowModal('form');
    }

    public function addCode()
    {
        $this->codes[] = ['id' => 0,'license' => ''];
    }

    public function updatedSearchProduct($value)
    {
        $this->search_result = collect($this->data['product'])->map(function ($item , $key) use ($value) {
             if (preg_match("/$value/i", $item)) {
                 return $item;
             }
             return null;
        })->filter(fn($item)=>!is_null($item))->toArray();

    }

    public function setProduct($id)
    {
        $this->product_id = $id;
        $last_history = ContainerHistory::latest('id')->where('product_id',$id)->where('action',LicenseEnum::ENTER)->first();
        if (!is_null($last_history)) {
            $this->enter_price = $last_history->enter_price;
        } else {
            $this->reset(['enter_price']);
        }
        $this->maxCount = ModelsContainer::isNotUsed($id)->count();
    }

    public function deleteCodeThroughHistory($key)
    {
        $this->authorizing('edit_container');
        if ($key != 0)
            $this->deleteFormContainer($key);
        else $this->emitNotify('کد با موقیت حذف شد');

        $this->codes = array_filter($this->codes , function($v,$k) use($key) {
            return $v['id'] != $key;
        },ARRAY_FILTER_USE_BOTH);
    }

    public function storeHistory()
    {
        if ($this->action == LicenseEnum::ENTER) {
            $validation = [
                'enter_price' => ['required','between:1,9999999999999.9999999'],
                'description' => ['nullable','string','max:1400'],
                'product_id' => [Rule::requiredIf(
                    $this->historyAction == 'new'
                ),'in:'.(implode(',',array_keys($this->data['product'])))],
                'codes' => ['array','min:1'],
                'codes.*.license' => ['required','string','max:250'],
            ];
            $messages = [
                'enter_price' => 'قیمت خرید',
                'description' => 'توضیحات',
                'product_id' => 'محصول',
                'codes' => 'کد ها',
                'codes.*.license' => 'کد ها',
            ];
            $count = count($this->codes);
        } else {
            $validation = [
                'count' => ['required','integer','between:0,999999999999999999'],
                'enter_price' => ['required','between:1,9999999999999.9999999'],
                'exit_price' => ['required','between:1,9999999999999.9999999'],
                'description' => ['nullable','string','max:1400'],
                'order_id' => ['required','string','max:250'],
                'product_id' => ['required','in:'.(implode(',',array_keys($this->data['product'])))],
            ];
            $messages = [
                'count' => 'تعداد',
                'enter_price' => 'قیمت خرید',
                'exit_price' => 'قیمت فروش',
                'description' => 'توضیحات',
                'order_id' => 'کد سفارش',
                'product_id' => 'محصول',
            ];

            $count = $this->count;
            if ($count > ModelsContainer::isNotUsed($this->product_id)->count() && $this->historyAction == 'new') {
                return $this->addError('count','موجودی کافی نمی باشد');
            }
        }
        $this->validate($validation ,[] ,$messages);
        try {
            DB::beginTransaction();
            if ($this->historyAction == 'new') {
                $history = ContainerHistory::create([
                    'action' => $this->action,
                    'count' => $count,
                    'enter_price' => $this->enter_price ?? 0,
                    'exit_price' => $this->exit_price ?? 0,
                    'order_id' => $this->order_id ?? 0,
                    'user_id' => auth()->id(),
                    'description' => $this->description,
                    'product_title' => $this->data['product'][$this->product_id],
                    'product_id' => $this->product_id,
                ]);
            } else {
                $history = $this->history_row;
                $history->update([
                    'count' => $count,
                    'enter_price' => $this->enter_price ?? 0,
                    'exit_price' => $this->exit_price ?? 0,
                    'order_id' => $this->order_id ?? 0,
                    'description' => $this->description,
                ]);
            }

            if ($this->action == LicenseEnum::ENTER) {
                foreach ($this->codes as $item) {
                    if ($item['id'] == 0) {
                        $history->enterContainers()->create([
                            'license' => $item['license'],
                            'product_id' => $this->product_id,
                            'product_title' => $this->data['product'][$this->product_id],
                            'status' => LicenseEnum::IS_NOT_USED,
                        ]);
                    } else {
                        $history->enterContainers()->where('id',$item['id'])->update([
                            'license' => $item['license'],
                        ]);
                    }
                }
                $result = ['form_key' => $history->id,'licenses'=> implode(" و ",array_value_recursive('license',$this->codes)) ];
                $this->emit('formResult',$result);
            } else {
                if ($this->historyAction == 'new') {
                    $licenses = ModelsContainer::isNotUsed($this->product_id)->take($count);
                    $result = ['form_key' => $history->id,'licenses'=> implode(" و ",array_value_recursive('license',$licenses->get()->toArray()))  ];
                    $licenses->update([
                        'status' => LicenseEnum::IS_USED,
                        'form_exit_id' => $history->id
                    ]);
                    $this->emit('formResult',$result);
                }
            }

            DB::commit();
            $this->emitHideModal('form');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->emitHideModal('form');
            $this->emitNotify('خطا در هنگام ثبت اطلاعات','warning');
        }
    }

    public function deleteHistory($id)
    {
        $this->authorizing('edit_container');
        ContainerHistory::destroy($id);
        $this->emitNotify('فرم با موقیت حذف شد');
    }
}
