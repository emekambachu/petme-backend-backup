<?php

namespace App\Services\Wallet;

use App\Models\Wallet\ServiceProviderWallet;
use App\Models\Wallet\UserWallet;
use App\Services\ServiceProvider\ServiceProviderService;
use App\Services\User\UserService;

/**
 * Class WalletService.
 */
class WalletService
{
    protected UserService $user;
    protected ServiceProviderService $service_provider;
    public function __construct(UserService $user, ServiceProviderService $service_provider)
    {
        $this->user = $user;
        $this->service_provider = $service_provider;
    }

    public function userWallet(): UserWallet
    {
        return New UserWallet();
    }

    public function walletByUserId($id){
        return $this->userWallet()->where('user_id', $id)->first();
    }

    public function serviceProviderWallet(): ServiceProviderWallet
    {
        return New ServiceProviderWallet();
    }

    public function walletByServiceProviderId($id){
        return $this->serviceProviderWallet()->where('service_provider_id', $id)->first();
    }

    public function createWallet($userId, $query): void
    {
        $query->create([
           'user_id' => $userId,
        ]);
    }

    public function fundWallet(Int $amount, string $type, $query){
        $wallet = $query;
        if($type === 'debit'){
            if($wallet->amount < $amount) {
                return [
                    'success' => false,
                    'message' => 'Insufficient funds, please fund wallet',
                ];
            }
            $wallet->amount -= $amount;
        }

        if($type === 'credit'){
            $wallet->amount += $amount;
        }

        $wallet->type = $type;
        $wallet->save();

        return $wallet->amount;
    }
}
