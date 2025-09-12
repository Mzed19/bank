<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Models\Deposit;
use App\Models\User;
use App\Services\Login\LoginService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
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

    public function create(UserStoreRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();

            $userCreated = User::create($data);

            Deposit::create([
                'receiver_id' => $userCreated->id,
                'amount' => $data['balance'],
                'description' => 'Depósito inicial'
            ]);

            return $this->sendContent(
                content: UserResource::make($userCreated),
                code: Response::HTTP_CREATED
            );
        }catch(Exception $e){
            return $this->treatException($e);
        }
    }

    public function all(): JsonResponse
    {
        $users = User::all();

        return $this->sendContent(
            content: UserResource::collection($users),
            code: $users->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK
        );
    }
}
