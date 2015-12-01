<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class forgotpassword implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * NOT AVAILABLE
     */
    public function get($pages)
    {
        return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));
    }

    /**
     * Resets a a frogotten password
     * @param array $pages
     *
     * @SWG\Post(
     *     summary="Reset a password",
     *     path="/v1/forgotpassword",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages)
    {
        $response = array();

        if (!isset($pages[0])) {
            $pages[0] = "request";
        }

        switch ($pages[0]) {
        case "request":
          $user = new Entities\User(strtolower($_POST['username']));
          if (!$user->guid) {
              $response['status'] = "error";
              $response['message'] = "Could not find @" . $_POST['username'];
              break;
          }
          $code = Core\Security\Password::reset($user);
          $link = elgg_get_site_url() . "forgot-password?username=" . $user->username . "&code=" . $code;

          //now send an email
          $mailer = new Core\Email\Mailer();
          $message = new Core\Email\Message();
          $message->setTo($user);
          $message->setSubject("Password Reset");
          $message->setHtml("Hello @$user->username. Please click on the following link to reset your password " . $link);
          $mailer->send($message);

          break;
        case "reset":
          $user = new Entities\User(strtolower($_POST['username']));
          if (!$user->guid) {
              $response['status'] = "error";
              $response['message'] = "Could not find @" . $_POST['username'];
              break;
          }

          if (!$user->password_reset_code) {
              $response['status'] = "error";
              $response['message'] = "Please try again with a new reset code.";
              break;
          }

          if ($user->password_reset_code && $user->password_reset_code != $_POST['code']) {
              $response['status'] = "error";
              $response['message'] = "The reset code is invalid";
              break;
          }

          $user->salt = Core\Security\Password::salt();
          $user->password = Core\Security\Password::generate($user, $_POST['password']);
          $user->password_reset_code = "";
          $user->override_password = true;
          $user->save();

          login($user);

          $response['user'] = $user->export();

          break;
        default:
          $response = array('status'=>'error', 'message'=>'Unknown endpoint');
      }

        return Factory::response($response);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
