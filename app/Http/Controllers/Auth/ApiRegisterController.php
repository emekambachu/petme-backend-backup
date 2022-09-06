<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserRegisterRequest;
use App\Services\Auth\RegistrationService;
use Illuminate\Http\Request;

class ApiRegisterController extends Controller
{
    private $registration;
    public function __construct(RegistrationService $registration){
        $this->registration = $registration;
    }

    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->registration->createUser($request);
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
            $this->registration->verifyWithToken($token);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
