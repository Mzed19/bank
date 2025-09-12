<?php

namespace App\Observers;

use App\Models\Transaction;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TransactionObserver
{
    public function created(Model $record): void
    {
        try {
            Transaction::create([
                'user_id' => $record->receiver_id,
                'amount' => $record->amount,
                'type' => Str::of($record->getTable())->singular()->value(),
                'imported_id' => $record->id
            ]);
        } catch (Exception $exception) {
            $this->revertTransaction(
                tableName: $record->getTable,
                recordId: $record->id
            );
            Log::critical("Não foi possível creditar um valor de $record->amount para o usuário $record->receiver_id");
            Log::critical($exception->getMessage());
        }

        if (!is_null($record->sender_id)) {
            try {
                Transaction::create([
                    'user_id' => $record->sender_id,
                    'amount' => -$record->amount,
                    'type' => Str::of($record->getTable())->singular()->value(),
                    'imported_id' => $record->id
                ]);
            } catch (Exception $exception) {
                $this->revertTransaction(
                    tableName: $record->getTable,
                    recordId: $record->id
                );
                Log::critical("Não foi possível debitar um valor de $record->amount para o usuário $record->sender_id");
                Log::critical($exception->getMessage());
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
