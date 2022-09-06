<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
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
    public function __construct(LoginService $login){
        $this->middleware('guest:admin')
            ->except('logout');
        $this->login = $login;
    }

    public function login(AdminLoginRequest $request){
        try {
            return $this->login->adminLoginAndToken($request);

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
