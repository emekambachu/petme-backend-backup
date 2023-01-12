<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserRegisterRequest;
use App\Http\Requests\User\Auth\UserSendEmailOtpRequest;
use App\Services\Auth\RegistrationService;
use App\Services\Base\BaseService;
use App\Services\User\UserService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;

class ApiRegisterController extends Controller
{
    private RegistrationService $registration;
    private UserService $user;
    public function __construct(
        RegistrationService $registration,
        UserService $user,
    ){
        $this->registration = $registration;
        $this->user = $user;
    }

    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->registration->createUser(
                $request,
                'emails.users.welcome',
                $this->user->user(),
                'user',
            );
            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Otp sent to '.$user->email,
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function submitOtp(Request $request){
        try {
            $data = $this->registration->submitOtpAndActivateAccount($request, $this->user->user());
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function sendOtpToUserEmail(UserSendEmailOtpRequest $request){
        try {
            $user = $this->user->userByEmail($request->email);
            if($user){
                $otp = $this->registration->generateOtpForUserById($user->id, $this->user->user());
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
