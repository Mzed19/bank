<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this['token'],
            'user' => [
                'id' => $this['user']['id'],
                'document' => $this['user']['document'],
            ],
        ];
    }
}
