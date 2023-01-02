<?php

namespace App\Http\Resources\v1;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    use DatabaseTransactions;
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
            'username' => $this->username,
            'phone' => $this->phone,
            'name' => $this->name,
            'token' => $this->token
        ];
    }
}
