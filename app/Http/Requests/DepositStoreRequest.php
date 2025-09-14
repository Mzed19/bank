<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiverAccountId' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ];
    }
}
