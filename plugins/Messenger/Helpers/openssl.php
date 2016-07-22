<?php
/**
 * Minds OpenSSL helper class
 */

namespace minds\plugin\gatherings\helpers;

class openssl
{

    /**
     * Returns a new keypair
     *
     * @param string $password
     * @return array - Public & Private
     */
    public static function newKeypair($password = null)
    {
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
    public static function encrypt($data, $public_key)
    {
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
    public static function decrypt($encrypted, $private_key, $password = null)
    {
        openssl_private_decrypt($encrypted, $decrypted, openssl_get_privatekey($private_key, $password));

        return $decrypted;
    }

    /**
     * Return a temporary key
     *
     * @param string $private_key - the protected private key
     * @param string $password - the password to unlock the private key
     * @param string $newpass (optional) - the new password for the temporary key
     * @return string - the new key
     */
    public static function temporaryPrivateKey($private_key, $password = null, $newpass = null)
    {
        $private_key = openssl_get_privatekey($private_key, $password);
        openssl_pkey_export($private_key, $pkeyout, $newpass);
        return $pkeyout;
    }

  /**
   * Verify the password for the key works
   */
  public static function verify($public_key, $private_key, $unlock_password = null)
  {
      $message = "hello world";
      $encrypted = self::encrypt($message, $public_key);
    //should fail
    $decrypted = self::decrypt($encrypted, $private_key);
      if ($decrypted == $message) {
          return false;
      }

    //should pass
    $decrypted = self::decrypt($encrypted, $private_key, $unlock_password);
      if ($decrypted == $message) {
          return true;
      }
  }
}
