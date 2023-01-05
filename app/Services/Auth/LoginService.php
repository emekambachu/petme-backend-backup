<?php

namespace App\Services\Auth;

use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\User\UserResource;
use App\Models\Admin\Admin;
use App\Models\User\User;
use App\Services\Account\AdminAccountService;
use App\Services\Account\UserAccountService;
use App\Services\Admin\AdminService;
use App\Services\ServiceProvider\ServiceProviderService;
use App\Services\User\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginService.
 */
class LoginService{

    protected $user;
    protected $provider;
    protected $admin;
    public function __construct(
        UserService $user,
        ServiceProviderService $provider,
        AdminService $admin
    ){
        $this->user = $user;
        $this->provider = $provider;
        $this->admin = $admin;
    }

    public function loginWithToken(
        $request,
        String $webGuard,
        String $apiGuard,
        $queryBuilder
    ): array
    {
        // Check if user is verified before attempting to login
        $status = $queryBuilder->where('email', $request->email)->first()->status;
        if($status !== 1){
            return [
                'success' => false,
                'message' => "Unverified User",
            ];
        }

        if(Auth::guard($webGuard)->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])){
            // get Session
            $user = Auth::guard($webGuard)->user();
            // Get Token
            $token = $user->createToken($request->email, [$apiGuard])->plainTextToken;

            // Last login
            $queryBuilder->where('email', $request->email)->update([
                'last_login' => Carbon::now()->format('Y-m-d h:i:s'),
            ]);

            $data = [
                'success' => true,
                'user' => new UserResource($user),
                'token' => $token,
                'message' => 'Correct credentials',
            ];
        }else{
            $data = [
                'success' => false,
                'message' => 'Incorrect credentials',
            ];
        }
        return $data;
    }
}
