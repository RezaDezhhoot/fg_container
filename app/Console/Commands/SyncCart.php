<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\UnsignedCart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-cart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $carts = Cart::query()
            ->whereNull('cart_number')
            ->take(20)
            ->get();
        if ($carts->count() > 0) {
            $conf = config('services.giftcartland');
            $res = Http::acceptJson()
                ->baseUrl($conf['baseurl'])
                ->withHeaders([
                    'authorization' => $conf['apiKey']
                ])->get('/v1/finance/signed-cards');
            if (! $res->successful()) {
                print_r($res->json());
                return 0;
            }
            $data = $res->json('data');
            UnsignedCart::query()->upsert( array_map(function ($v) {
                return [
                    'cart_id' => $v['id'],
                    'masked_pan' => $v['masked_pan'],
                    'name' => $v['showing_name'],
                ];
            }, $data) , ['cart_id']);

            $unsignedCarts = UnsignedCart::query()
                ->oldest('id')
                ->take($carts->count())
                ->where('used' , false)
                ->get();
            if ($unsignedCarts->count() > 0) {
                foreach ($carts as $k => $cart) {
                    if (! isset($unsignedCarts[$k])) {
                        break;
                    }
                    $c = $unsignedCarts[$k];
                    $res = Http::acceptJson()
                        ->baseUrl($conf['baseurl'])
                        ->withHeaders([
                            'authorization' => $conf['apiKey']
                        ])->get('/v1/finance/card-detail/' . $c['cart_id']);
                    if (! $res->successful()) {
                        continue;
                    }
                    try {
                        DB::beginTransaction();
                        $cartDetails = $res->json('data.card');
                        $cart->fill([
                            'cart_number' => $c['masked_pan'],
                            'cart_cvv2' => $cartDetails['cvv'],
                            'expire' => $cartDetails['expiry_year'].'/'.$cartDetails['expiry_year'],
                            'balance' => $cartDetails['total_balance']
                        ])->save();
                        $c->fill(['used' => true])->save();
                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        report($exception);
                    }
                }
            }
        }
    }
}
