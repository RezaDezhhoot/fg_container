<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PanelToken;
use App\Http\Resources\v1\CartResource;
use App\Models\Cart;
use App\Models\Panel;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index(PanelToken $panelToken)
    {
        $panel = Panel::query()->whereNotNull('token')->where('token',$panelToken->get('token'))->firstOrFail();
        $carts = Cart::query()->latest()->where('panel_id',$panel->id)->when($panelToken->filled('count'),function ($q) use ($panelToken){
           return $q->take($panelToken->get('count'));
        })->cursor();
        return response()->json(
            [
                'data' => CartResource::collection($carts)
            ]
        );
    }

    public function update(PanelToken $panelToken)
    {

    }
}
