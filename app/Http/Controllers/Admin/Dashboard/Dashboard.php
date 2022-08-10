<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Enums\LicenseEnum;
use App\Http\Controllers\BaseComponent;
use App\Models\Container;
use App\Models\ContainerHistory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Dashboard extends BaseComponent
{
    public $from_date , $to_date , $box ;

    public $to_date_viwe , $from_date_view;

    protected $queryString = ['to_date','from_date'];

    public function mount()
    {
        if (
            !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->to_date) ||
            !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->from_date)
        )
        {
            $this->reset(['to_date','from_date']);
        }

        if (!isset($this->to_date)){
            $this->to_date =  Carbon::now()->format('Y-m-d');
        }
        $this->to_date_viwe = $this->dateConverter($this->to_date);

        if (!isset($this->from_date)){
            $this->from_date = Carbon::now()->subDays(5)->format('Y-m-d');
        }
        $this->from_date_view = $this->dateConverter($this->from_date);



        $this->getData();
    }

    public function confirmFilter()
    {
        $from_date = $this->dateConverter($this->from_date_view,'m');
        $to_date = $this->dateConverter($this->to_date_viwe ,'m');
        redirect()->route('dashboard',
            [
                'from_date'=> $from_date,
                'to_date'=> $to_date,
            ]
        );
    }

    public function runChart()
    {
        $this->emit('runChart',$this->getChartData());
    }

    public function getData()
    {
        $this->box = [
            'all'=> Container::count(),
            'used'=> Container::isNotUsed()->count(),
            'not_used'=> Container::isUsed()->count(),
            'deleted'=> Container::onlyTrashed()->count(),
        ];
    }

    public function getChartData(): bool|string
    {
        $dates = $this->getDates();
        $chart = [];
        $chartModels = [
            LicenseEnum::ENTER => ContainerHistory::class,
            LicenseEnum::EXIT => ContainerHistory::class,
        ];
        foreach ($chartModels as $key => $class ) {
            $chart[$key] = [];
            $chart['label'] = [];
            for ($i = 0 ; $i< count($dates); $i++) {
                $chart[$key][] =  (new $class)->where('action',$key)
                    ->whereBetween('created_at',[$dates[$i]->format('Y-m-d') . " 00:00:00", $dates[$i]->format('Y-m-d') . " 23:59:59" ])
                    ->select('count')
                    ->sum('count');
                $chart['label'][] = (string)$dates[$i]->format('Y-m-d');
            }
        }
        return json_encode($chart);
    }

    public function getDates(): array
    {
        $period = CarbonPeriod::create($this->from_date, $this->to_date);
        foreach ($period as $date) {
            $date->format('Y-m-d');
        }
        return $period->toArray();
    }

    public function render()
    {
        return view('admin.dashboard.dashboard')->extends('admin.includes.admin');
    }
}
