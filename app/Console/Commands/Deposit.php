<?php

namespace App\Console\Commands;

use App\Models\CartCharge;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Deposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deposit';

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
        $items = CartCharge::query()
            ->whereHas('cart' , function ($q) {
                $q->whereHas('cart');
            })
            ->with(['cart','cart.cart'])
            ->where('confirm' , false)
            ->take(5)
            ->get();
        $conf = config('services.giftcartland');

        foreach ($items as $item) {
            $res = Http::acceptJson()
                ->baseUrl($conf['baseurl'])
                ->withHeaders([
                    'authorization' => $conf['apiKey']
                ])->post('/v1/finance/change-card-balance/'.$item->cart->cart_id , [
                    'mode' => 1,
                    'amount' => $item->amount
                ]);
            if (! $res->successful()) {
                print_r($res->json());
                continue;
            }
            $item->update([
                'confirm' => true
            ]);
            $item->cart->cart->increment('balance' , $item->amount);
        }

        return Command::SUCCESS;
    }
}
