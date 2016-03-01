<?php
/**
 * Minds Email Provider
 */

namespace Minds\Core\Email;

use Minds\Core\Di\Provider;

class EmailProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Mailer', function($di){
            return new Mailer(new \PHPMailer());
        }, ['useFactory'=>true]);
    }

}
