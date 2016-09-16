<?php
/**
 * Minds OpenSSL Encryption
 */

namespace Minds\Plugin\Messenger\Core\Encryption;

use Minds\Plugin\Messenger;

class OpenSSL implements EncryptionInterface
{
    private $keystore;
    private $conversation;
    private $user;
    private $password;
    private $message;

    public function __construct($keystore = null)
    {
        $this->keystore = $keystore ?: new Messenger\Core\Keystore($this);
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setConversation($conversation)
    {
        $this->conversation = $conversation;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setUnlockPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function encrypt()
    {
        $messages = [];
        foreach ($this->conversation->getParticipants() as $guid => $user) {
            $public_key = $this->keystore->setUser($user)->getPublicKey();

            if (!$public_key) {
                $messages[$guid] = false;
                continue; // Avoid log warnings
            }

            openssl_public_encrypt($this->message->getMessage(), $encrypted, $public_key);
            $messages[$guid] = base64_encode($encrypted);
        }
        $this->message->setMessages($messages);
        return $this;
    }

    public function decrypt()
    {
        $encrypted = $this->message->getMessage($this->user->guid);
        $private_key = $this->keystore->setUser($this->user)->getUnlockedPrivateKey();
        openssl_private_decrypt(base64_decode($encrypted), $decrypted, openssl_get_privatekey($private_key, $this->password));

        $this->message->setMessages([$this->user->guid => $decrypted], false);
        $this->message->setMessage($decrypted, false);
        return $this;
    }

    public function generateKeypair($password = "tmp")
    {
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            'encrypt_key' => true
        ];

        $res = openssl_pkey_new($config);

        //private key
        openssl_pkey_export($res, $privKey, $password);

        //public key
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey['key'];

        return [
          'public' => $pubKey,
          'private' => $privKey
        ];
    }

    public function unlockPrivateKey($private_key, $password, $newpass = "")
    {
        $private_key = openssl_get_privatekey($private_key, $password);
        if (!$private_key) {
            throw new \Exception('Could not decrypt private key');
        }
        openssl_pkey_export($private_key, $pkeyout, $newpass);
        return $pkeyout;
    }
}
