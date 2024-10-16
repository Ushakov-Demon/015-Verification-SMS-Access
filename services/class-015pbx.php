<?php

class PBX_015
{
    public  $url = "https://www.015pbx.net/local/api/json/messages/text/send/";
    private $crypt;
    private $username;
    private $password;
    private $snumber;
    private $data_args = [];
    public  $message_response;

    function __construct( string $message = "", string $cnumber = "", int $code = 0 ) {
        $this->crypt            = new AAV_CRYPTO();
        $this->username         = $this::getUsername();
        $this->password         = $this::getPassword();
        $this->snumber          = $this::getSnumber();
        $this->data_args        = $this::setDataArgs( $message, $cnumber );
        $this->message_response = $this::sendMessage( $code );
    }

    private function getUsername() {
        return $this->crypt->Decode( AAV_015_AUTH_USERNAME );
    }

    private function getPassword() {
        return $this->crypt->Decode( AAV_015_AUTH_PASSWORD );
    }

    private function getSnumber() {
        return $this->crypt->Decode( AAV_015_SNUMBER );
    }

    private function setDataArgs( string $message = "", string $cnumber = "" ) {
        $data = array(
            'auth_username' => $this->username,
            'auth_password' => $this->password,
            'stype' 		=> '',
            'snumber' 		=> $this->snumber,
            'cnumber' 		=> $cnumber,
            'message' 		=> urlencode( $message ),
        );

        return $data;
    }

    private function sendMessage( $code ) {
        $curl = curl_init();
        $out  = [];

        curl_setopt_array( $curl, [
            CURLOPT_URL 			=> $this->url,
            CURLOPT_RETURNTRANSFER 	=> true,
            CURLOPT_POST 			=> true,
            CURLOPT_POSTFIELDS 		=> http_build_query( $this->data_args ),
            CURLOPT_HTTPHEADER 		=> [
                ],
        ] );
    
        $response = curl_exec( $curl );
        curl_close( $curl );
    
        if ( curl_errno( $curl ) ) {
            $out['ERROR'] = curl_error( $curl );
        } else {
            $responses = json_decode( $response )->responses[0];
            if ( 204 !== $responses->code ) {
                $out['ERROR'] = $responses->code;
            } else {
                $out['SUCCESS'] = $code;
            }
        }

        return $out;
    }
}