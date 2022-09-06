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
    public function verificationToken($length = 11): string
    {
        $characters = '0123456789ABCDEFG';
        $charactersLength = strlen($characters);
        $randomString = 'PET';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createUser($request){
        $input = $request->all();
        $input['verification_token'] = $this->verificationToken();
        $input['password'] = Hash::make($input['password']);
        $user = $this->user()->create($input);

        // Send email verification
        $this->sendVerificationEmail($user);
        return $user;
    }

    public function sendVerificationEmail($createdUser): void
    {
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

    public function verifyWithToken($token): \Illuminate\Http\JsonResponse
    {
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
