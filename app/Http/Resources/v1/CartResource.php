<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cart_number' => $this->cart_number,
            'category_name' => $this->category->cart_label ?? 'نامشخص',
            'cart_cvv2' => $this->cart_cvv2,
            'image' => $this->image,
            'expire' => $this->expire,
            'category_id' => $this->category_id,
            'panel_id' => $this->panel_id,
            'price' => 0,
            'is_new' => $this->is_new,
            'symbol' => $this->category->currency->symbol ?? 't',
            'type' => $this->type,
            'is_charged' => $this->is_charged,
            'balance' => $this->balance
        ];
    }
}
