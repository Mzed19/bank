<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiverId' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ];
    }
}
