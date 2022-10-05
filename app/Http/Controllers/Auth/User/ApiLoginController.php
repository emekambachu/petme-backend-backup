<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserLoginRequest;
use App\Services\Auth\LoginService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    private $login;
    private $user;
    public function __construct(LoginService $login, UserService $user){
        $this->middleware('guest:web')
            ->except('logout');
        $this->login = $login;
        $this->user = $user;
    }

    public function login(UserLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->login->loginWithToken(
                $request,
                'web',
                'api',
                $this->user->user()
            );
            return response()->json([
                'success' => $data['success'] ?? null,
                'user' => $data['user'] ?? null,
                'token' => $data['token'] ?? null,
                'message' => $data['message'] ?? null,
                'errors' => $data['errors'] ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(){
        try {
            Auth::guard('web')->logout();
            Auth::user()->tokens()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logged Out',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
