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
    protected $signature   = 'app:chaos';
    protected $description = 'It will start many transactions simulated with stored users.';

    private Collection $accounts;

    public function handle()
    {
        $this->accounts = Account::all();
        $transferTypes  = TransferTypeEnum::cases();

        $tokens = $this->accounts->mapWithKeys(fn ($account) => [
            $account->id => JWTAuth::fromUser($account)
        ]);

        $totalRequests = 100;

        $responses = Http::pool(function (Pool $pool) use ($tokens, $transferTypes, $totalRequests) {
            $reqs = [];

            for ($i = 0; $i < $totalRequests; $i++) {
                $account = $this->getRandomAccount();
                $token   = $tokens[$account->id]; // token já gerado

                $reqs[] = $pool
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

            return $reqs;
        });

        foreach ($responses as $idx => $response) {
            if ($response->failed()) {
                $this->error("Falha na requisição #{$idx}: HTTP " . $response->status());
            }
        }

        $this->info('Envio concluído!');
    }

    private function getRandomAccount(): Account
    {
        return $this->accounts[random_int(0, $this->accounts->count() - 1)];
    }
}
