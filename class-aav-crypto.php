<?php
/*
 * See https://www.php.net/manual/en/book.openssl.php docs
 */ 
class AAV_CRYPTO
{
    private $cryptoKey;
    private $cipher;
    private $options;

    public function __construct( string $str = '', string $cipher = 'aes-256-cbc' )
    {
        $this->cryptoKey = $this::secretKeyConvert( "CWAoBSmbYgCJGB1R" );
        $this->cipher    = $cipher;
        $this->options   = 0;
    }

    private function secretKeyConvert( $key )
    {
        $keyLength = strlen( $key );
	    if ( $keyLength < 16 ) {
	        $key = str_pad( $key, 16, "0" );
	    } elseif ( $keyLength > 16 ) {
	        $key = substr( $key, 0, 16 );
	    }

	    if ( ctype_xdigit( $key ) ) {
	        $key = hex2bin( $key );
	    }

	    return $key;
    }

    public function Encode( $str )
    {
    	if(strlen( trim( $str ) ) == 0){
    		return trim( $str );
    	}

        $secret_key = $this->cryptoKey;
        $ivlen      = openssl_cipher_iv_length( $this->cipher );
        $iv         = random_bytes( $ivlen );

        $encrypted = openssl_encrypt( $str, $this->cipher, $secret_key, $this->options, $iv );
        if ( false === $encrypted ) {
            throw new Exception( "Encryption failed: " . openssl_error_string() );
        }

        $hmac = hash_hmac( 'sha256', $encrypted, $secret_key );
        return base64_encode( $encrypted . '::' . $iv . '::' . $hmac );
    }

    public function Decode( $str )
    {
    	if( strlen( trim( $str ) ) == 0 ){
    		return trim( $str );
    	}

        $secret_key  = $this->cryptoKey;
        $decodedData = base64_decode( $str);

        $parts = explode( '::', $decodedData, 3 );

        if( count( $parts ) < 3 ){
        	return $str;
        }

        $encrypted_data = $parts[0];
        $stored_iv      = $parts[1];
        $stored_hmac    = $parts[2];

        $calcmac = hash_hmac( 'sha256', $encrypted_data, $secret_key );
        if ( ! hash_equals( $stored_hmac, $calcmac ) ) {
            throw new Exception( "Authentication error: data is corrupted" );
        }

        $decrypted = openssl_decrypt( $encrypted_data, $this->cipher, $secret_key, $this->options, $stored_iv );
        if ( false === $decrypted ) {
            throw new Exception( "Decryption failed: " . openssl_error_string() );
        }
        return $decrypted;
    }
}