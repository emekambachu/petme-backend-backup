<?php

namespace App\Services\Auth;

use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\User\UserResource;
use App\Models\Admin\Admin;
use App\Models\User\User;
use App\Services\Account\AdminAccountService;
use App\Services\Account\UserAccountService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginService.
 */
class LoginService{

    public static function user(){
        return new User();
    }

    public static function userWithRelations(){
        return self::user()->with('user_appointments', 'pets');
    }

    public static function admin(){
        return new Admin();
    }

    public static function adminLoginAndToken($request){

        if(Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])){
            $user = Auth::guard('admin')->user();
            $token = $user->createToken($user->email.' token', ['admin-api'])->plainTextToken;
            $response = [
                'success' => true,
                'user' => new AdminResource($user),
                'token' => $token,
                'message' => 'Correct credentials',
            ];
        }else{
            $response = [
                'success' => false,
                'message' => 'Incorrect credentials',
                'status' => 401,
            ];
        }

        return response()->json($response);
    }

    public static function userLoginAndToken($request){

        // Check if user is verified before attempting to login
        $verified = self::user()
            ->where('email', $request->email)->first()->verified;
        if($verified !== 1){
            return response()->json([
                'success' => false,
                'errors' => ['unverified', 'Unverified User'],
            ]);
        }

        if(Auth::guard('web')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])){
            // get Session
            $user = Auth::guard('web')->user();
            // Get Token
            $token = $user->createToken($user->email.' token', ['api'])->plainTextToken;

            // Last login
            self::user()->where('email', $request->email)->update([
                    'last_login' => Carbon::now()->format('Y-m-d h:i:s'),
                ]);

            $response = [
                'success' => true,
                'user' => new UserResource($user),
                'token' => $token,
                'message' => 'Correct credentials',
            ];
        }else{
            $response = [
                'success' => false,
                'message' => 'Incorrect credentials',
                'status' => 401,
            ];
        }

        return response()->json($response);
    }
}
