<?php

namespace App\Services\Auth;

use App\Services\Account\UserAccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Class RegistrationService.
 */
class RegistrationService extends UserAccountService
{
    //Generate email verification token
    public static function verificationToken($length = 11){
        $characters = '0123456789ABCDEFG';
        $charactersLength = strlen($characters);
        $randomString = 'PET';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function createUser($request){
        $input = $request->all();
        $input['verification_token'] = self::verificationToken();
        $input['password'] = Hash::make($input['password']);
        return self::user()->create($input);
    }

    public static function sendVerificationEmail($createdUser){

        $user = [
            'name' => $createdUser->name,
            'email' => $createdUser->email,
            'verification_token' => $createdUser->verification_token,
        ];

        Mail::send('emails.users.verification', $user, static function ($message) use ($user) {
            $message->from('info@petme.tech', 'Pet Me');
            $message->to($user['email'], $user['name']);
            $message->replyTo('info@petme.tech', 'Pet Me');
            $message->subject('Account verification');
        });
    }

    public static function verifyWithToken($token){
        $userVerified = self::user()->where('verification_token', $token)->first();
        if($userVerified){
            $userVerified->update([
               'verified' => 1
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Account verified, you can now login',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Incorrect Token',
        ]);
    }
}
