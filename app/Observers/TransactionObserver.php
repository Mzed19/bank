<?php

namespace App\Observers;

use App\Models\Transaction;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TransactionObserver
{
    public function created(Model $record): void
    {
        try {
            Transaction::create([
                'account_id' => $record->receiver_account_id,
                'amount' => $record->amount,
                'type' => Str::of($record->getTable())->singular()->value(),
                'imported_id' => $record->id
            ]);
        } catch (Exception $exception) {
            Log::critical("Não foi possível creditar um valor de $record->amount para o usuário $record->receiver_account_id");
            Log::critical($exception->getMessage());
            $this->revertTransaction(
                tableName: $record->getTable(),
                recordId: $record->id
            );
            throw new UnprocessableEntityHttpException('Não foi possível concluir a transação.');
        }

        if (!is_null($record->sender_account_id)) {
            try {
                Transaction::create([
                    'account_id' => $record->sender_account_id,
                    'amount' => -$record->amount,
                    'type' => Str::of($record->getTable())->singular()->value(),
                    'imported_id' => $record->id
                ]);
            } catch (Exception $exception) {
                $this->revertTransaction(
                    tableName: $record->getTable(),
                    recordId: $record->id
                );
                Log::critical("Não foi possível debitar um valor de $record->amount para o usuário $record->receiver_account_id");
                Log::critical($exception->getMessage());
                throw new UnprocessableEntityHttpException('Não foi possível concluir a transação.');

            }
        }
    }

    private function revertTransaction(string $tableName, int $recordId): void
    {
        try{
            DB::delete("delete $tableName where id = $recordId");
        }catch(Exception $exception){
            Log::critical("Falha ao remover o registro $recordId da tabela $tableName");
        }
    }
}
