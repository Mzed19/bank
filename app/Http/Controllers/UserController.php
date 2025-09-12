<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function create(UserStoreRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();

            $userCreated = User::create($data);

            Deposit::create([
                'user_id' => $userCreated->id,
                'amount' => $data['balance'],
                'description' => 'Depósito inicial'
            ]);

            return $this->sendContent(
                content: UserResource::make($userCreated),
                code: Response::HTTP_CREATED
            );
        }catch(Exception $e){
            return $this->sendContent(
                content: $e->getMessage(),
                code: Response::HTTP_BAD_REQUEST
            );
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
