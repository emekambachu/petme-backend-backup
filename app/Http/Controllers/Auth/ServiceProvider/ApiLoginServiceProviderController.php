<?php

namespace App\Http\Controllers\Auth\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceProvider\Auth\ServiceProviderLoginRequest;
use App\Http\Requests\User\Auth\UserLoginRequest;
use App\Services\Auth\LoginService;
use App\Services\ServiceProvider\ServiceProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoginServiceProviderController extends Controller
{
    private $login;
    private $provider;
    public function __construct(LoginService $login, ServiceProviderService $provider){
        $this->middleware('guest:service-provider')
            ->except('logout');
        $this->login = $login;
        $this->provider = $provider;
    }

    public function login(ServiceProviderLoginRequest $request){
        try {
            $data = $this->login->loginWithToken(
                $request,
                'service-provider',
                'service-provider-api',
                $this->provider->serviceProvider()
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
            Auth::guard('service-provider')->logout();
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
