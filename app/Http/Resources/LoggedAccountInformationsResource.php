<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoggedAccountInformationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'accountId' => $this['accountId'],
            'document' => $this['document'],
            'balance' => $this['balance'],
            'balanceFormatted' => $this['balanceFormatted']
        ];
    }
}
