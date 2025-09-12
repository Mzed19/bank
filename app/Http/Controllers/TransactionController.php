<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositStoreRequest;
use App\Http\Requests\TransferStoreRequest;
use App\Http\Resources\DepositResource;
use App\Http\Resources\TransferResource;
use App\Models\Deposit;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TransactionController extends Controller
{
    public function createDeposit(DepositStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $depositCreated = Deposit::create([
                'user_id' => $data['userId'],
                'amount' => $data['amount'],
                'description' => $data['description'] ?? null
            ]);

            return $this->sendContent(
                content: DepositResource::make($depositCreated),
                code: Response::HTTP_CREATED
            );
        }catch (Exception $exception) {
            return $this->treatException($exception);
        }
    }

    public function createTransfer(TransferStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            (new TransactionService)->createTransfer(
                amount: $data['amount'],
                userId: $data['userId'],
                description: $data['description'] ?? null
            );

            return $this->sendContent(
                content: TransferResource::make(),
                code: Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
           return $this->treatException($exception);
        }
    }
}
