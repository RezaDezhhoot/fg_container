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
        $response  = json_decode('
        {"data":{"products":{"records":[
        {"id":3969,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u067e\u0644\u06cc \u0627\u0633\u062a\u06cc\u0634\u0646 (PSN)"},
        {"id":5481,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u067e\u0644 \u0627\u0633\u062a\u0648\u0631 - \u0622\u06cc\u062a\u0648\u0646\u0632 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":5483,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645"},
        {"id":49125,"title":"\u0627\u0634\u062a\u0631\u0627\u06a9 \u0627\u06cc\u06a9\u0633 \u0628\u0627\u06a9\u0633 \u0644\u0627\u06cc\u0648 14 \u0631\u0648\u0632\u0647"},
        {"id":58200,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 25 \u062f\u0644\u0627\u0631\u06cc psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58202,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 30 \u062f\u0644\u0627\u0631\u06cc psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58205,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 40 \u062f\u0644\u0627\u0631\u06cc psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58211,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 20 \u062f\u0644\u0627\u0631\u06cc Xbox"},
        {"id":58216,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 40 \u062f\u0644\u0627\u0631\u06cc Xbox"},
        {"id":58218,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 50 \u062f\u0644\u0627\u0631\u06cc Xbox"},
        {"id":58223,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 10 \u062f\u0644\u0627\u0631\u06cc \u0627\u067e\u0644 \u0627\u0633\u062a\u0648\u0631 - \u0622\u06cc\u062a\u0648\u0646\u0632 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58226,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 15 \u062f\u0644\u0627\u0631\u06cc \u0627\u067e\u0644 \u0627\u0633\u062a\u0648\u0631 - \u0622\u06cc\u062a\u0648\u0646\u0632 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58227,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 50 \u062f\u0644\u0627\u0631\u06cc \u0627\u067e\u0644 \u0627\u0633\u062a\u0648\u0631 - \u0622\u06cc\u062a\u0648\u0646\u0632 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58232,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0622\u0645\u0627\u0632\u0648\u0646 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58237,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 15 \u062f\u0644\u0627\u0631\u06cc \u0622\u0645\u0627\u0632\u0648\u0646 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58239,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 50 \u062f\u0644\u0627\u0631\u06cc \u0622\u0645\u0627\u0632\u0648\u0646 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":58247,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0646\u06cc\u062a\u0646\u062f\u0648 10 \u062f\u0644\u0627\u0631\u06cc"},
        {"id":58249,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0646\u06cc\u062a\u0646\u062f\u0648 20 \u062f\u0644\u0627\u0631\u06cc"},
        {"id":58251,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0646\u06cc\u0646\u062a\u0646\u062f\u0648 50 \u062f\u0644\u0627\u0631\u06cc"},
        {"id":62757,"title":"\u067e\u0644\u0627\u0633 \u0633\u0647 \u0645\u0627\u0647\u0647 psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":62819,"title":"\u067e\u0644\u0627\u0633 \u06cc\u06a9 \u0633\u0627\u0644\u0647 psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":74984,"title":"\u0633\u0631\u0648\u06cc\u0633 \u06a9\u0627\u0647\u0634 \u067e\u06cc\u0646\u06af | Exitlag"},
        {"id":173908,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 10 \u062f\u0644\u0627\u0631\u06cc \u06af\u0648\u06af\u0644 \u067e\u0644\u06cc"},
        {"id":173918,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u06af\u0648\u06af\u0644 \u067e\u0644\u06cc \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":173920,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 15 \u062f\u0644\u0627\u0631\u06cc \u06af\u0648\u06af\u0644 \u067e\u0644\u06cc"},
        {"id":174484,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 50 \u062f\u0644\u0627\u0631\u06cc psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":174496,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 5 \u067e\u0648\u0646\u062f\u06cc psn \u0627\u0646\u06af\u0644\u06cc\u0633"},
        {"id":174506,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 20 \u067e\u0648\u0646\u062f\u06cc psn \u0627\u0646\u06af\u0644\u06cc\u0633"},
        {"id":174512,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 10 \u067e\u0648\u0646\u062f\u06cc PSN \u0627\u0646\u06af\u0644\u06cc\u0633"},
        {"id":187183,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 100 \u062f\u0644\u0627\u0631\u06cc psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187184,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 10 \u062f\u0644\u0627\u0631\u06cc psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187186,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 5 \u062f\u0644\u0627\u0631\u06cc psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187192,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 10 \u062f\u0644\u0627\u0631\u06cc psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187193,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 15 \u062f\u0644\u0627\u0631\u06cc psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187194,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 20 \u062f\u0644\u0627\u0631\u06cc psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187196,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 100 \u062f\u0644\u0627\u0631\u06cc psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187197,"title":"\u067e\u0644\u0627\u0633 \u06cc\u06a9 \u0645\u0627\u0647\u0647 psn \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187198,"title":"\u067e\u0644\u0627\u0633 \u06cc\u06a9 \u0645\u0627\u0647\u0647 psn \u0627\u0646\u06af\u0644\u0633\u062a\u0627\u0646"},
        {"id":187199,"title":"\u067e\u0644\u0627\u0633 \u0633\u0647 \u0645\u0627\u0647\u0647 psn \u0627\u0646\u06af\u0644\u0633\u062a\u0627\u0646"},
        {"id":187200,"title":"\u067e\u0644\u0627\u0633 \u06cc\u06a9 \u0633\u0627\u0644\u0647 psn \u0627\u0646\u06af\u0644\u0633\u062a\u0627\u0646"},
        {"id":187201,"title":"\u067e\u0644\u0627\u0633 \u06cc\u06a9 \u0645\u0627\u0647\u0647 psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187202,"title":"\u067e\u0644\u0627\u0633 \u0633\u0647 \u0645\u0627\u0647\u0647 psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187203,"title":"\u067e\u0644\u0627\u0633 \u06cc\u06a9 \u0633\u0627\u0644\u0647 psn \u0627\u0645\u0627\u0631\u0627\u062a"},
        {"id":187204,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 5 \u062f\u0644\u0627\u0631\u06cc \u0627\u067e\u0644 \u0627\u0633\u062a\u0648\u0631 - \u0622\u06cc\u062a\u0648\u0646\u0632 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187205,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 20 \u062f\u0644\u0627\u0631\u06cc \u0627\u067e\u0644 \u0627\u0633\u062a\u0648\u0631 - \u0622\u06cc\u062a\u0648\u0646\u0632 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187207,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 5 \u062f\u0644\u0627\u0631\u06cc Xbox"},{"id":187208,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 10 \u062f\u0644\u0627\u0631\u06cc Xbox"},
        {"id":187209,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 15 \u062f\u0644\u0627\u0631\u06cc Xbox"},
        {"id":187211,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 5 \u062f\u0644\u0627\u0631\u06cc \u06af\u0648\u06af\u0644 \u067e\u0644\u06cc"},
        {"id":187212,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 20 \u062f\u0644\u0627\u0631\u06cc \u06af\u0648\u06af\u0644 \u067e\u0644\u06cc"},
        {"id":187213,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 1 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187214,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 2 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187215,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 5.1 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187216,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 6 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187217,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 10 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187218,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 12 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187219,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 15 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187220,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 20 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187223,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 40 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187224,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a \u0627\u0633\u062a\u06cc\u0645 50 \u062f\u0644\u0627\u0631\u06cc \u0647\u0645\u0647 \u06a9\u0634\u0648\u0631 \u0647\u0627"},
        {"id":187226,"title":"\u0627\u0634\u062a\u0631\u0627\u06a9 \u06cc\u06a9 \u0645\u0627\u0647\u0647 PS Now \u067e\u0644\u06cc \u0627\u0633\u062a\u06cc\u0634\u0646 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187227,"title":"\u0627\u0634\u062a\u0631\u0627\u06a9 \u0633\u0647 \u0645\u0627\u0647\u0647 PS Now \u067e\u0644\u06cc \u0627\u0633\u062a\u06cc\u0634\u0646 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187228,"title":"\u0627\u0634\u062a\u0631\u0627\u06a9 \u06cc\u06a9 \u0633\u0627\u0644\u0647 PS Now \u067e\u0644\u06cc \u0627\u0633\u062a\u06cc\u0634\u0646 \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187236,"title":"\u0645\u0627\u0646\u06cc\u062a\u0648\u0631 Msi G24C4"},{"id":187264,"title":"295 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187265,"title":"525 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187266,"title":"1200 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187267,"title":"1850 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187268,"title":"4375 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187269,"title":"6300 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187275,"title":"1000 Apex Coins"},{"id":187276,"title":"2150 Apex Coins"},{"id":187277,"title":"4350 Apex Coins"},
        {"id":187321,"title":"\u0627\u0634\u062a\u0631\u0627\u06a9 1 \u0645\u0627\u0647\u0647 \u0627\u06af\u0632\u06cc\u062a \u0644\u06af"},
        {"id":187322,"title":"\u0627\u0634\u062a\u0631\u0627\u06a9 3 \u0645\u0627\u0647\u0647 \u0627\u06af\u0632\u06cc\u062a \u0644\u06af"},
        {"id":187323,"title":"\u0627\u0634\u062a\u0631\u0627\u06a9 6 \u0645\u0627\u0647\u0647 \u0627\u06af\u0632\u06cc\u062a \u0644\u06af"},
        {"id":187350,"title":"1000 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0627\u0631\u0648\u067e\u0627"},
        {"id":187352,"title":"2050 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0627\u0631\u0648\u067e\u0627"},
        {"id":187353,"title":"5350 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0627\u0631\u0648\u067e\u0627"},
        {"id":187354,"title":"475 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187355,"title":"1000 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187356,"title":"2050 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187357,"title":"3650 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187358,"title":"5350 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187359,"title":"11000 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u0622\u0645\u0631\u06cc\u06a9\u0627"},
        {"id":187414,"title":"115 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187415,"title":"485 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187416,"title":"925 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187417,"title":"5550 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187418,"title":"3400 \u0639\u062f\u062f \u067e\u0648\u06cc\u0646\u062a \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187503,"title":"20 \u0644\u06cc\u0631 \u0627\u0633\u062a\u06cc\u0645 \u0631\u06cc\u062c\u0646 \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187504,"title":"50 \u0644\u06cc\u0631 \u0627\u0633\u062a\u06cc\u0645 \u0631\u06cc\u062c\u0646 \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187505,"title":"100 \u0644\u06cc\u0631 \u0627\u0633\u062a\u06cc\u0645 \u0631\u06cc\u062c\u0646 \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187506,"title":"200 \u0644\u06cc\u0631 \u0627\u0633\u062a\u06cc\u0645 \u0631\u06cc\u062c\u0646 \u062a\u0631\u06a9\u06cc\u0647"},
        {"id":187516,"title":"\u0628\u0631\u06af\u0631 \u06a9\u06cc\u0646\u06af | Burger King"},
        {"id":187517,"title":"\u0628\u0627\u0646\u062f\u0644 \u062c\u06a9 \u0644\u06cc\u0646\u06a9 | jack link"},
        {"id":187531,"title":"20 \u0644\u06cc\u0631 \u0627\u0633\u062a\u06cc\u0645 \u0631\u06cc\u062c\u0646 \u062a\u0631\u06a9\u06cc\u0647-copy"},
        {"id":187544,"title":"\u06af\u06cc\u0641\u062a \u06a9\u0627\u0631\u062a 10 \u062f\u0644\u0627\u0631\u06cc psn \u0622\u0645\u0631\u06cc\u06a9\u0627-copy"},
        {"id":187230,"title":"Valorant Point"}
        ]}},"status":"success"}
        ',true);
        $products = collect($response['data']['products']['records'])
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
