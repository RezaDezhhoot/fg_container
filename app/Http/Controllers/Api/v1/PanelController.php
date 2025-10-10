<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PanelToken;
use App\Http\Resources\v1\CartResource;
use App\Models\Cart;
use App\Models\CartCharge;
use App\Models\Panel;
use App\Models\UnsignedCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function show($id)
    {
        $cart = UnsignedCart::query()->where('masked_pan' , $id)->firstOrFail();
        $conf = config('services.giftcartland');
        $res = Http::acceptJson()
            ->baseUrl($conf['baseurl'])
            ->withHeaders([
                'authorization' => $conf['apiKey']
            ])->get('/v1/finance/card-detail/' . $cart->cart_id);
        if (! $res->successful()) {
            return response()->json([
                'message' => $res->json()
            ],404);
        }
        return response()->json($res->json());
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => ['required','min:1','max:10000'],
            'panel_id' => ['required','exists:panels,id'],
            'number' => ['required'],
        ]);
        $unsignedCart = UnsignedCart::query()->where('masked_pan' , $request->number)->where('used', true)->firstOrFail();
        $data = CartCharge::query()->create([
            ... $request->only(['amount','panel_id']),
            'unsigned_cart_id' => $unsignedCart->id
        ]);
        return response()->json(
            [
                'data' => $data
            ]
        );
    }

    public function update(PanelToken $panelToken)
    {

    }
}
