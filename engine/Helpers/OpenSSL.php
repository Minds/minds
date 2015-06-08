<?php
/**
 * Minds OpenSSL helper class
 */
 
namespace Minds\Helpers;

class OpenSSL{
    
    /**
     * Returns a new keypair
     * 
     * @param string $password
     * @return array - Public & Private
     */
    static public function newKeypair($password = NULL){
            
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            'encrypt_key' => true
        );
            
        $res = openssl_pkey_new($config);
        
        //private key
        openssl_pkey_export($res, $privKey, $password);
        
        //public key
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];
        
        return array(
            'public' => $pubKey,
            'private' => $privKey
            );
    }
    
    /**
     * Encrypts data
     * 
     * @param string $data
     * @param string $public_key
     * @return string
     */
    static public function encrypt($data, $public_key){
        
        openssl_public_encrypt($data, $encrypted, $public_key);
        
        return $encrypted;
        
    }
    
    /**
     * Decrypt data
     * 
     * @param string $encrypted
     * @param string $private_key
     * @param string @password - default = ''
     * @return string
     */
    static public function decrypt($encrypted, $private_key, $password = NULL){
            
        openssl_private_decrypt($encrypted, $decrypted, openssl_get_privatekey($private_key, $password));
        
        return $decrypted;
    }
    
}
