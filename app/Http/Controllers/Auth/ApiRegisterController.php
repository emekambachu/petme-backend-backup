<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserRegisterRequest;
use App\Services\Auth\RegistrationService;
use Illuminate\Http\Request;

class ApiRegisterController extends Controller
{
    public function register(UserRegisterRequest $request){

        try {
            $user = RegistrationService::createUser($request);
            RegistrationService::sendVerificationEmail($user);
            return response()->json([
                'success' => true,
                'message' => 'Verification email sent to '.$user->email,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function verifyAccount($token){
        try {
            RegistrationService::verifyWithToken($token);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
