<?php
/**
 * Minds Captcha API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class captcha implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Get a captcha question
     *
     */
    public function get($pages)
    {
        $captcha = Core\Di\Di::_()->get('Security\Captcha');

        $response = [
          'question' => $captcha->getQuestion()
        ];

        return Factory::response($response);
    }


    public function post($pages)
    {
        $captcha = Core\Di\Di::_()->get('Security\Captcha');

        $success = $captcha->validateAnswer($_POST['type'], $_POST['question'], $_POST['answer'], $_POST['nonce'], $_POST['hash']);
        return Factory::response([ 'success' => $success ]);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
