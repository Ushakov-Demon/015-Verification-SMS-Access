<?php

class AAV_TWILIO
{
    private $crypt;
    private $sid;
    private $token;
    private $from;
    private $url = "https://api.twilio.com/2010-04-01/Accounts/";
    public  $response;

    function __construct( string $message = "", string $tonumber = "", int $code = 0 ) {
        $this->crypt     = new AAV_CRYPTO();
        $this->sid       = $this::getSID();
        $this->token     = $this::getToken();
        $this->from      = $this::getFromNum();
        $this->url      .= $this->sid . "/Messages.json?From=" . $this->from;
        $this->response  = $this::sendMessage( $message, $tonumber );
    }

    private function getSID() {
        return $this->crypt->Decode( TWILIO_ACCOUNT_SID );
    }

    private function getToken() {
        return $this->crypt->Decode( TWILIO_ACCOUNT_TOKEN );
    }

    private function getFromNum() {
        return $this->crypt->Decode( TWILIO_NUMBER_FROM );
    }

    private function bodyParamsCreate( string $mess_str, string $to, string $from ) {
        $params = [
            "Body" => $mess_str,
            "To"   => $to,
            "From" => $from,
        ];

        return $params;
    }

    private function sendMessage( $message, $tonumber, $code ) {
        $body_args   = $this::bodyParamsCreate( $message, $tonumber, $this->from );
        $body        = http_build_query( $body_args, '', '&', PHP_QUERY_RFC3986 );
        $auth        = base64_encode( $this->sid . ':' . $this->token );
        $out         = [];
        $curl        = curl_init();

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
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Basic $auth"
            ),
        ) );

        $response = curl_exec( $curl );

        if ( curl_errno( $curl ) ) {
            $out['ERROR'] = curl_error( $curl );
        }

        curl_close( $curl );

        $data = json_decode( $response );

        if( ! is_null( $data['error_message'] ) ) {
            $out['ERROR'] = $data['error_message'];
        } else if ( is_null( $data['error_message'] ) && is_null( $data['error_code'] ) ) {
            $out['SUCCESS'] = $code;
        }

        return $out;
    }
}