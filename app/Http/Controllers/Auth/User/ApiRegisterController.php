<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserRegisterRequest;
use App\Http\Requests\User\Auth\UserSendEmailOtpRequest;
use App\Services\Auth\RegistrationService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class ApiRegisterController extends Controller
{
    private $registration;
    private $user;
    public function __construct(RegistrationService $registration, UserService $user){
        $this->registration = $registration;
        $this->user = $user;
    }

    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->registration->createUser(
                $request,
                'emails.user.welcome',
                $this->user->user()
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

    public function submitOtp(Request $request){
        try {
            $data = $this->registration->submitOtpAndActivateAccount($request, $this->user->user());
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
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
