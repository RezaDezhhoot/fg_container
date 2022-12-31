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
            'cart_cvv2' => $this->cart_cvv2,
            'image' => $this->image,
            'expire' => $this->expire,
            'category_id' => $this->category_id,
            'panel_id' => $this->panel_id,
            'price' => $this->category->price,
            'symbol' => $this->category->currency->symbol ?? 't',
            'type' => $this->type
        ];
    }
}
