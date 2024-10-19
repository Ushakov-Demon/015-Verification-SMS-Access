<?php
class AAV_SMSMODE
{
    private $url = "https://rest.smsmode.com/sms/v1/messages";
    private $crypt;
    private $api_key;
    public $response;

    function __construct( $message, $tonumber, $code ){
        $this->crypt     = new AAV_CRYPTO();
        $this->api_key   = $this::getApiKey();
        $this->response  = $this::sendMessage( $message, $tonumber, $code );
    }

    private function getApiKey() {
        return $this->crypt->Decode( SMSMODE_APIKEY );
    }

    private function setBody( $message, $number ) {
        $resp = [
            "recipient" => [
                "to" => $number,
            ],
            "body" => [
                "text" => $message,
            ],
        ];

        return json_encode( $resp );
    }

    private function sendMessage( $message, $tonumber, $code ) {
        $out         = [];
        $curl        = curl_init();
        $body        = $this::setBody( $message, $tonumber );
        $apiKey      = $this->api_key;

        curl_setopt_array( $curl, array(
            CURLOPT_URL             => $this->url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $body,
            CURLOPT_HTTPHEADER      => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "X-Api-Key: $apiKey",
            ),
        ) );

        $response = curl_exec( $curl );

        if ( curl_errno( $curl ) ) {
            $out['ERROR'] = curl_error( $curl );
        }

        $httpCode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

        curl_close( $curl );

        $data = json_decode( $response );

        if( $httpCode !== 201 ) {
            $out['ERROR'] = $data->message;
        } else {
            $out['SUCCESS'] = $code;
        }

        return $out;
    }

}