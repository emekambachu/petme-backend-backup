<?php

namespace App\Services\Auth;

use App\Services\Base\ThirdPartyApiService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Class RegistrationService.
 */
class RegistrationService
{
    protected WalletService $wallet;
    protected ThirdPartyApiService $api;
    public function __construct(
        WalletService $wallet,
        ThirdPartyApiService $api
    ){
        $this->wallet = $wallet;
        $this->api = $api;
    }

    //Generate email verification token
    public function generateToken($length = 6): string
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createUser($request, $emailContent, $userQuery, $walletQuery){
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $userQuery->create($input);

        // Create wallet
        $this->wallet->createWallet($user->id, $walletQuery);

        // Send welcome and otp emails
        $otp = $this->generateOtpForUserById($user->id, $userQuery);
        $this->api->termiiEmailApi($user->email, (int)$otp);

//        $this->sendWelcomeEmail($user, $emailContent);
//        $this->sendOtpEmail($user, $otp);

        // Return user
        return $user;
    }

    private function sendWelcomeEmail($createdUser, String $emailContent): void
    {
        $user = [
            'name' => $createdUser->name,
            'email' => $createdUser->email,
        ];
        $this->sendEmail($user, $emailContent, 'Welcome to Pet Me');
    }

    public function generateOtpForUserById($id, $queryBuilder): string
    {
        $otp = $this->generateToken();
        $user = $queryBuilder->findOrFail($id);
        $user->verification_token = $otp;
        $user->token_used = false;
        $user->save();
        return $user->verification_token;
    }

    public function sendOtpEmail($user, $otp): void
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
