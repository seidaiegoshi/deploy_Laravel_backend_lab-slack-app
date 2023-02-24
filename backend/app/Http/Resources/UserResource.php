<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'nickname' => $this->nickname,
            'email' => $this->email,
            'icon_url' => $this->icon_path
                ? route('web.users.image', ['userId' => $this->id])
                : null,
        ];
    }
}
