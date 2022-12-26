<?php

namespace App\Services\Base;

/**
 * Class ThirdPartyApiService.
 */
class ThirdPartyApiService
{
    public function termiiEmailApi($mailTo, $message){

        $curl = curl_init();
        $data = [
            "email_address" => $mailTo,
            "code" => $message,
            "api_key" => config('app.termii_api_key'),
            "email_configuration_id" => config('app.termii_email_config_id')
        ];

        dd($data);

        $post_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.ng.termii.com/api/email/otp/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,

            // Comment before moving to production
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,

            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // Comment before moving to production
//        echo $response;
    }
}
