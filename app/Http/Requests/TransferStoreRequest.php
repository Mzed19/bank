<?php

namespace App\Http\Requests;

use App\Enums\TransferTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class TransferStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiverId' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'string', 'in:'.TransferTypeEnum::getValuesInString()],
        ];
    }
}
