<?php

namespace App\Services\Wallet;

use App\Models\Wallet\ServiceProviderWallet;
use App\Models\Wallet\UserWallet;
use App\Services\ServiceProvider\ServiceProviderService;
use App\Services\User\UserService;

/**
 * Class WalletService.
 */
class WalletService
{
    protected UserService $user;
    protected ServiceProviderService $service_provider;
    public function __construct(UserService $user, ServiceProviderService $service_provider)
    {
        $this->user = $user;
        $this->service_provider = $service_provider;
    }

    public function userWallet(): UserWallet
    {
        return New UserWallet();
    }

    public function walletByUserId($id){
        return $this->userWallet()->where('user_id', $id)->first();
    }

    public function serviceProviderWallet(): ServiceProviderWallet
    {
        return New ServiceProviderWallet();
    }

    public function walletByServiceProviderId($id){
        return $this->serviceProviderWallet()->where('service_provider_id', $id)->first();
    }

    public function createWallet($userId, $query): void
    {
        $query->create([
           'user_id' => $userId,
        ]);
    }

    /**
     * @throws \JsonException
     */
    public function fundWallet($request, $user): array
    {
        $url = "https://api.paystack.co/transaction/initialize";
        $fields = [
            'first_name' => $user->name,
            'email' => $user->email,
            'amount' => $request->amount * 100, //convert to kobo,
            'metadata' => [
                'user_id' => $user->id,
                'name' => $user->name
            ]
        ];

        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        // Comment before moving to production
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer ".@env('PAYSTACK_SECRET_KEY'),
            "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        //execute post
        $data = json_decode(curl_exec($ch), true);

        if(isset($data) && $data['status'] === true){
            return [
                'success' => true,
                'amount' => $request->amount,
                'access_code' => $data['data']['access_code'],
                'reference' => $data['data']['reference'],
                'paystack_url' => $data['data']['authorization_url'],
            ];
        }
        return [
            'success' => false,
            'message' => "Error connecting to API",
        ];
    }

    public function verifyWalletTransaction($reference, $query): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",

            // Comment before moving to production
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,

            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".@env('PAYSTACK_SECRET_KEY'),
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        if($data['data']['status'] === 'success'){

            $wallet = $query;
            // If this transaction has already been stored
            if($wallet->last_reference === $data['data']['reference']){
                return [
                    'success' => false,
                    'message' => 'Transaction already exists',
                ];
            }

            $userId = (int)$data['data']['metadata']['user_id'];
            $amount = $data['data']['amount'] / 100; // convert back to naira

            // fund wallet after transaction reference verification
            $wallet->amount += $amount;
            $wallet->last_reference = $data['data']['reference'];
            $wallet->save();

            return [
                'success' => true,
                'status' => $data['data']['status'],
                'reference' => $data['data']['reference'],
                'amount' => $amount,
            ];
        }
        return [
            'success' => false,
            'message' => $err,
        ];
    }

}
