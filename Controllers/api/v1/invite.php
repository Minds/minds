<?php
/**
 * Minds Invite API
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

/**
 *
 * Invite API
 *
 * Endpoint: /v1/invite/
 *
 */
// @codingStandardsIgnoreStart
class invite implements Interfaces\Api
{

    // @codingStandardsIgnoreEnd

    use \Minds\Traits\HttpMethodsInput;
    use \Minds\Traits\CurrentUser;

    /**
     * Not used
     */
    public function get($pages)
    {
        return Factory::response(array());
    }

    /**
     * POST method handler
     * @param  array $pages
     * @return string
     */
    public function post($pages)
    {

        $response = [ 'done' => false ];

        if (static::hasPostValue('contacts') && is_array(static::getPostValue('contacts'))) {
            foreach (static::getPostValue('contacts') as $contact) {
                $sent = $this->sendInviteEmail($contact);

                if ($sent) {
                    $response['done'] = true;
                }
            }
        }

        if (static::hasPostValue('contact') && is_array(static::getPostValue('contact'))) {
            $sent = $this->sendInviteEmail(static::getPostValue('contact'));

            if ($sent) {
                $response['done'] = true;
            }
        }

        return Factory::response($response);

    }

    /**
     * Not used
     */
    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * Not used
     */
    public function delete($pages)
    {
        return Factory::response(array());
    }

    /**
     * Sends the invitation email to a single email address
     * @param  array  $contact
     * @return mixed
     */
    protected function sendInviteEmail($contact)
    {

        if (empty($contact['emails'][0]['value'])) {
            return false;
        }

        $user = static::getCurrentUser();

        $email = $contact['emails'][0]['value'];
        $name = !empty($contact['name']['formatted']) ? empty($contact['name']['formatted']) : 'friend';

        $site = elgg_get_site_entity();

        $from_email = $site->email;
        $from_name = $site->name;

        $subject = "{$user->name} invited you to Minds";
        $body = elgg_view('emails/invite', [ 'user' => $user, 'name' => $name ]);

        $sent = phpmailer_send($from->email, $from_name, $email, $name, $subject, $body, null, true);

        return $sent;

    }

}
