<?php

namespace App\Http\Requests;

use App\Enums\TransferTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferStoreRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'receiverId' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'string', Rule::in(TransferTypeEnum::cases())],
        ];
    }
}
