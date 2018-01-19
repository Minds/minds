<?php
/**
 * Minds API for onboarding to the Rewards System
 */

namespace Minds\Controllers\api\v1\rewards;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Interfaces;

class phone implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        $twofactor = new Core\Security\TwoFactor();

        if (isset($pages[0])) {
            switch ($pages[0]) {
                case 'check':
                    return Factory::response([
                        'onboarded' => (bool) Core\Session::getLoggedinUser()->getPhoneNumberHash()
                    ]);
                    break;
                case 'verify':
                    if (!isset($_POST['phone'])) {
                        return Factory::response(['status' => 'error', 'message' => 'phone field is required']);
                    }

                    $phone = $_POST['phone'];

                    if (!isset($_POST['code'])) {
                        return Factory::response(['status' => 'error', 'message' => 'code field is required']);
                    }
                    $code = $_POST['code'];

                    if (!isset($_POST['secret'])) {
                        return Factory::response(['status' => 'error', 'message' => 'code field is required']);
                    }
                    $secret = $_POST['secret'];

                    if ($twofactor->verifyCode($secret, $code, 1)) {
                        $user = Core\Session::getLoggedinUser();

                        $user->setPhoneNumber($phone);

                        $user->save();
                        return Factory::response([
                            'status' => 'success',
                            'message' => 'You have successfully onboarded to Minds Rewards System'
                        ]);
                    } else {
                        return Factory::response(['status' => 'error', 'message' => 'Wrong code']);
                    }
                    break;
            }
        }

        if (!isset($_POST['phone'])) {
            return Factory::response(['status' => 'error', 'message' => 'phone field is required']);
        }

        $phone = $_POST['phone'];

        $secret = $twofactor->createSecret();
        $code = $twofactor->getCode($secret);

        Core\Di\Di::_()->get('SMS')->send($phone, $code);

        return Factory::response(['status' => 'success', 'secret' => $secret]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}