<?php

namespace App\Http\Controllers\User\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Wallet\UserFundWalletRequest;
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

    public function fund(UserFundWalletRequest $request){
        try {
            $data = $this->wallet->fundWallet($request, Auth::user());
            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function verifyTransaction($reference){
        try {
            $data = $this->wallet->verifyWalletTransaction($reference, $this->wallet->walletByUserId(Auth::user()->id));
            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
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
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }


}
