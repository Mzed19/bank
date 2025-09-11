<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document' => 'required|string|cpf_ou_cnpj|unique:users,document',
            'password' => 'required|string|min:5',
            'balance' => 'required|numeric|min:0'
        ];
    }
}
