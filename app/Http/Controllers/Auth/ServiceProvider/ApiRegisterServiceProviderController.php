<?php

namespace App\Http\Controllers\Auth\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceProvider\Auth\ServiceProviderRegisterRequest;
use App\Services\Auth\RegistrationService;
use App\Services\ServiceProvider\ServiceProviderService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;

class ApiRegisterServiceProviderController extends Controller
{
    private RegistrationService $registration;
    private ServiceProviderService $provider;
    private WalletService $wallet;
    public function __construct(
        RegistrationService $registration,
        ServiceProviderService $provider,
        WalletService $wallet
    ){
        $this->registration = $registration;
        $this->provider = $provider;
        $this->wallet = $wallet;
    }

    public function register(ServiceProviderRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->registration->createUser(
                $request,
                'emails.service-providers.welcome',
                $this->provider->serviceProvider(),
                $this->wallet->serviceProviderWallet()
            );
            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Otp sent to '.$user->email,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function submitOtp(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->registration->submitOtpAndActivateAccount(
                $request,
                $this->provider->serviceProvider()
            );
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
