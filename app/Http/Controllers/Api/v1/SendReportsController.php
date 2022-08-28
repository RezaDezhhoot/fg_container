<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\LicenseEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResourceCollection;
use App\Models\ContainerHistory;

class SendReportsController extends Controller
{
    public function __invoke()
    {
        $products = ContainerHistory::select('action','count','product_id')->get();
        $product_list = [];
        foreach ($products as $value) {
            !isset($product_list[$value->product_id]) && $product_list[$value->product_id] = 0;
            $product_list[$value->product_id] += 
                $value->action == LicenseEnum::ENTER ?  $value->count : -$value->count;
        }
        return response([
            'data' => new ProductResourceCollection(collect($product_list)),
            'status' => 'success'
        ],200);
    }
}
