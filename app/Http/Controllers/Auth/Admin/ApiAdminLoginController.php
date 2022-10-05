<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Http\Resources\User\UserResource;
use App\Services\Admin\AdminService;
use App\Services\Auth\LoginService;
use Illuminate\Support\Facades\Auth;

class ApiAdminLoginController extends Controller
{
    /**
     * @var LoginService
     */


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $login;
    private $admin;
    public function __construct(LoginService $login, AdminService $admin){
        $this->middleware('guest:admin')
            ->except('logout');
        $this->login = $login;
        $this->admin = $admin;
    }

    public function login(AdminLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->login->loginWithToken(
                $request,
                'admin',
                'admin-api',
                $this->admin->admin()
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
            Auth::guard('admin')->logout();
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
