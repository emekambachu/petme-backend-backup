<?php

namespace App\Http\Controllers\User\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Wallet\UserFundWalletRequest;
use App\Services\Base\BaseService;
use App\Services\User\UserService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWalletController extends Controller
{
    protected WalletService $wallet;
    protected UserService $user;
    public function __construct(WalletService $wallet, UserService $user){
        $this->wallet = $wallet;
        $this->user = $user;
    }

    public function fund(UserFundWalletRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->wallet->fundWallet($request, Auth::user());
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function verifyTransaction($reference): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->wallet->verifyWalletTransaction($reference, $this->wallet->walletByUserId(Auth::user()->id));
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function balance(){
        try {
            $data = $this->wallet->walletByUserId(Auth::user()->id);
            return response()->json([
                'success' => true,
                'balance' => $data->amount
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }


}
