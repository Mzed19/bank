<?php

namespace App\Console\Commands;

use App\Enums\TransferTypeEnum;
use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChaosTestCommand extends Command
{
    protected $signature   = 'simulate';
    protected $description = 'It will start many transactions simulated with stored users.';

    private Collection $accounts;

    public function handle()
    {
        $this->accounts = Account::all();
        $transferTypes  = TransferTypeEnum::cases();

        $tokens = $this->accounts->mapWithKeys(fn ($account) => [
            $account->id => JWTAuth::fromUser($account)
        ]);

        $totalRequests = 1000;

        Http::pool(function (Pool $pool) use ($tokens, $transferTypes, $totalRequests) {
            for ($i = 0; $i < $totalRequests; $i++) {
                $account = $this->getRandomAccount();
                $token   = $tokens[$account->id];

                $pool
                    ->withHeaders([
                        'Accept'        => 'application/json',
                        'Authorization' => "Bearer $token",
                    ])
                    ->post('http://192.168.18.13:8000/api/accounts/transactions/transfers', [
                        'receiverAccountId' => $this->getRandomAccount()->id,
                        'amount'            => random_int(0, 1000),
                        'type'              => $transferTypes[random_int(0, count($transferTypes) - 1)],
                    ]);
            }
        });

        $this->info('Simulação completa, verifique o endpoint geral de transações.');
    }

    private function getRandomAccount(): Account
    {
        return $this->accounts[random_int(0, $this->accounts->count() - 1)];
    }
}
