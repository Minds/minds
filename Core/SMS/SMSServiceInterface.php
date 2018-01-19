<?php
namespace Minds\Core\SMS;

interface SMSServiceInterface
{

    /**
     * Send an SMS
     * @param $number
     * @param $message
     * @return string - id
     */
    public function send($number, $message);

}