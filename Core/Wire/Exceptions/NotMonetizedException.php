<?php
/**
 * Created by Marcelo.
 * Date: 28/07/2017
 */

namespace Minds\Core\Wire\Exceptions;


class NotMonetizedException extends \Exception
{
    public function __construct() {
        $this->message = 'Sorry, this user cannot receive USD.';
    }
}