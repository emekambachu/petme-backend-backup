<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;

class ApiAdminLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(){
        $this->middleware('guest:admin-api')
            ->except('logout');
    }

    public function login(AdminLoginRequest $request){
        try {
            return LoginService::adminLoginAndToken($request);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }
}
