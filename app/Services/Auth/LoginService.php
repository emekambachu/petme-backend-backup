<?php

namespace App\Services\Auth;

use App\Http\Resources\Admin\AdminResource;
use App\Services\Account\AdminAccountService;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginService.
 */
class LoginService extends AdminAccountService {

    public static function adminLoginAndToken($request){

        $user = self::admin()->where('email', $request->email)->first();

        if(!$user || !Hash::check($request->email, $user->password)){
            $response = [
                'success' => false,
                'message' => 'Incorrect credentials',
                'status' => 401,
            ];
        }

        $token = $user->createToken($user->email.' token')->plainTextToken;
        $response = [
            'success' => true,
            'user' => new AdminResource($user),
            'token' => $token,
            'message' => 'Correct credentials',
        ];
        return response()->json($response);
    }


}
