<?php
/**
 * Minds Two Factor
 *
 * ---  ORIGINAL --
 *
 * 	PHP Class for handling Google Authenticator 2-factor authentication
 *
 * 	@author Michael Kliewe
 * 	@copyright 2012 Michael Kliewe
 * 	@license http://www.opensource.org/licenses/bsd-license.php BSD License
 * 	@link http://www.phpgangsta.de/
 *
 * ----------------
 */
namespace Minds\Core\Security;

use Minds\Core;

class TwoFactor
{
    protected $_codeLength = 6;

    /**
     * Create new secret.
     * 16 characters, randomly chosen from the allowed base32 characters.
     *
     * @param int $secretLength
     * @return string
     */
    public function createSecret($secretLength = 16)
    {
        $bytes = openssl_random_pseudo_bytes(64); 
        return base64_encode($bytes);
    }

    /**
     * Calculate the code, with given secret and point in time
     *
     * @param string $secret
     * @param int|null $timeSlice
     * @return string
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey = base64_decode($secret);

        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        // Hash it with users secret key
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        // Use last nipple of result as index/offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        // grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);

        // Unpak binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, $this->_codeLength);
        return str_pad($value % $modulo, $this->_codeLength, '0', STR_PAD_LEFT);
    }

    /**
     * Check if the code is correct. This will accept codes starting from $discrepancy*30sec ago to $discrepancy*30sec from now
     *
     * @param string $secret
     * @param string $code
     * @param int $discrepancy This is the allowed time drift in 30 second units (8 means 4 minutes before or after)
     * @return bool
     */
    public function verifyCode($secret, $code, $discrepancy = 1)
    {
        $currentTimeSlice = floor(time() / 30);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($calculatedCode == $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set the code length, should be >=6
     *
     * @param int $length
     * @return PHPGangsta_GoogleAuthenticator
     */
    public function setCodeLength($length)
    {
        $this->_codeLength = $length;
        return $this;
    }

}
