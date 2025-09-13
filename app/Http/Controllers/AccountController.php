<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\AccountResource;
use App\Models\Deposit;
use App\Models\Account;
use App\Services\Login\LoginService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try{
            $authentication = (new LoginService)->execute(
                document: $request->input('document'),
                password: $request->input('password')
            );

            return $this->sendContent(
                content: LoginResource::make($authentication),
                code: Response::HTTP_OK
            );
        }catch(Exception $e){
            return $this->treatException($e);
        }
    }

    public function create(AccountStoreRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();

            $accountCreated = Account::create($data);

            Deposit::create([
                'receiver_account_id' => $accountCreated->id,
                'amount' => $data['balance'],
                'description' => 'Depósito inicial'
            ]);

            return $this->sendContent(
                content: AccountResource::make($accountCreated),
                code: Response::HTTP_CREATED
            );
        }catch(Exception $e){
            return $this->treatException($e);
        }
    }

    public function all(): JsonResponse
    {
        $accounts = Account::all();

        return $this->sendContent(
            content: AccountResource::collection($accounts),
            code: $accounts->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK
        );
    }
}
