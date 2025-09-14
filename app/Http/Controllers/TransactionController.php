<?php

namespace App\Http\Controllers;

use App\Enums\TransferTypeEnum;
use App\Http\Requests\DepositStoreRequest;
use App\Http\Requests\TransferStoreRequest;
use App\Http\Resources\DepositResource;
use App\Http\Resources\TransferResource;
use App\Models\Deposit;
use App\Services\Transaction\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function createDeposit(DepositStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $depositCreated = Deposit::create([
                'receiver_account_id' => $data['receiverAccountId'],
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

            $transferCreated = (new TransactionService)->transfer(
                amount: $data['amount'],
                receiverId: $data['receiverAccountId'],
                type: TransferTypeEnum::from($data['type']),
                description: $data['description'] ?? null
            );

            return $this->sendContent(
                content: TransferResource::make($transferCreated),
                code: Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return $this->treatException($exception);
        }
    }
}
