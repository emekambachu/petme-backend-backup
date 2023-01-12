<?php

namespace App\Http\Controllers\Auth\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceProvider\Auth\ServiceProviderEmailOtpRequest;
use App\Http\Requests\ServiceProvider\Auth\ServiceProviderRegisterRequest;
use App\Services\Auth\RegistrationService;
use App\Services\Base\BaseService;
use App\Services\ServiceProvider\ServiceProviderService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;

class ApiRegisterServiceProviderController extends Controller
{
    private RegistrationService $registration;
    private ServiceProviderService $provider;
    public function __construct(
        RegistrationService $registration,
        ServiceProviderService $provider
    ){
        $this->registration = $registration;
        $this->provider = $provider;
    }

    public function register(ServiceProviderRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->registration->createUser(
                $request,
                'emails.service-providers.welcome',
                $this->provider->serviceProvider(),
                'service-provider'
            );
            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Otp sent to '.$user->email,
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
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
            return BaseService::tryCatchException($e);
        }
    }

    public function sendOtpToUserEmail(ServiceProviderEmailOtpRequest $request){
        try {
            $user = $this->provider->serviceProviderByEmail($request->email);
            if($user){
                $otp = $this->registration->generateOtpForUserById($user->id, $this->provider->serviceProvider());
                $this->registration->sendOtpEmail($user, $otp);
                return response()->json([
                    'success' => true,
                    'message' => "Email Otp sent to ".$user->email
                ]);
            }

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

}
