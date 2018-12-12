<?php
namespace Minds\Core\SMS;

interface SMSServiceInterface
{
    /**
     * Verifies the number isn't from a voip line
     * @param $number
     * @return boolean
     */
    public function verify($number);

    /**
     * Send an SMS
     * @param $number
     * @param $message
     * @return string - id
     */
    public function send($number, $message);

}
