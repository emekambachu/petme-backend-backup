<?php

namespace App\Services\Auth;

use App\Services\Account\UserAccountService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Class RegistrationService.
 */
class RegistrationService
{
    //Generate email verification token
    protected function generateToken($length = 8): string
    {
        $characters = '0123456789ABCDEFGHIJ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createUser($request, $emailContent, $queryBuilder){
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $queryBuilder->create($input);

        // Send welcome and otp emails
        $this->sendWelcomeEmail($user, $emailContent);
        $otp = $this->generateOtpForUserById($user->id, $queryBuilder);
        $this->sendOtpEmail($user, $otp);

        // Return user
        return $user;
    }

    protected function sendWelcomeEmail($createdUser, String $emailContent): void
    {
        $user = [
            'name' => $createdUser->name,
            'email' => $createdUser->email,
        ];
        $this->sendEmail($user, $emailContent, 'Welcome to Pet Me');
    }

    protected function generateOtpForUserById($id, $queryBuilder){
        $otp = $this->generateToken();
        $user = $queryBuilder->findOrFail($id);
        $user->verification_token = $otp;
        $user->token_used = false;
        $user->save();
        return $user->verification_token;
    }

    protected function sendOtpEmail($user, $otp): void
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'otp' => $otp,
        ];
        $this->sendEmail($data, 'emails.general.send-otp', 'OTP | Account Activation');
    }

    public function submitOtpAndActivateAccount($request, $queryBuilder): array
    {
        $user = $queryBuilder->where('verification_token', $request->otp)->first();
        if($user){
            if($user->token_used === 1){
                return [
                    'success' => false,
                    'message' => 'Token used, generate another.',
                ];
            }
            $user->status = 'verified';
            $user->token_used = 1;
            $user->save();
            return [
                'success' => true,
                'message' => 'Account activated',
            ];
        }
        return [
            'success' => false,
            'message' => 'Incorrect OTP, try again',
        ];
    }

    protected function sendEmail(Array $data, String $emailContent, String $subject): void
    {
        Mail::send($emailContent, $data, static function ($message) use ($data, $subject) {
            $message->from(@config('app.mail_from'), @config('app.name'));
            $message->to($data['email'], $data['name']);
            $message->replyTo(@config('app.mail_from'), @config('app.name'));
            $message->subject($subject);
        });
    }

}
