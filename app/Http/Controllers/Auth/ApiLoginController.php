<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Http\Requests\User\Auth\UserLoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    private $login;
    public function __construct(LoginService $login){
        $this->middleware('guest:web')
            ->except('logout');
        $this->login = $login;
    }

    public function login(UserLoginRequest $request){
        try {
            return $this->login->userLoginAndToken($request);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(){
        try {
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
