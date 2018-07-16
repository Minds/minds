<?php
/**
 * Minds Webhook: AWS SES
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\webhooks;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Helpers;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

class awssns implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * GET
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        $message = Message::fromRawPostData();
        $validator = new MessageValidator();

        if (!$validator->isValid($message)) {
            error_log('[AWS-SES] Invalid Amazon SNS message');
            return Factory::response([ 'status' => 'error' ]);
        }

        $snsSecret = Di::_()->get('Config')->get('sns_secret');

        if (!$snsSecret || $pages[0] !== $snsSecret) {
            error_log('[AWS-SES] Received request, but got an invalid secret key');
            return Factory::response([ 'status' => 'error' ]);
        }

        // Check if we're getting a subscription confirmation URL
        if ($message['Type'] === 'SubscriptionConfirmation') {
            // Dump to the error log
            error_log('[AWS-SES] Subscribed to URL: ' . $message['SubscribeURL']);

            // Subscribe
            if (stripos($message['SubscribeURL'], 'https:') === 0) {
                /** @var Core\Http\Curl\Client $http */
                $http = Di::_()->get('Http');

                $http->get($message['SubscribeURL']);
            }

            return Factory::response([ ]);
        }

        $notification = json_decode($message['Message'], true);

        // Should we proceed?
        $unsubscribe = false;

        switch ($notification['notificationType']) {
            case 'Bounce':
                if ($notification['bounce']['bounceType'] == 'Permanent') {
                    $unsubscribe = true;
                }
                // @todo: maybe add a bounces limit for Transient bounce types? Counter should be on User entity.
                break;
            case 'Complaint':
                // @todo: log complain reason: http://docs.aws.amazon.com/ses/latest/DeveloperGuide/notification-contents.html#complaint-object
                $unsubscribe = true;
                break;
        }

        $guid = null;
        $emailHash = null;
        $emailId = null;

        if (isset($notification['mail']['commonHeaders']['messageId'])) {
            // <[userGUID]-[emailAddressSHA1]-[emailGUID]@minds.com>
            $prefix = '@minds.com';
            $messageId = trim($notification['mail']['commonHeaders']['messageId'], '<>');

            if (strrpos($messageId, $prefix) === strlen($messageId) - strlen($prefix)) {
                $id = explode('-', explode('@', $messageId)[0]);

                if (count($id) === 3) {
                    $guid = $id[0];
                    $emailHash = $id[1];
                    $emailId = $id[2]; // Not used yet
                }
            }
        }

        if (!$guid || !$emailHash || !$unsubscribe) {
            return Factory::response([ 'status' => 'error' ]);
        }

        // Unsubscribe
        $user = new Entities\User($guid);

        if (!$user || !$user->guid) {
            return Factory::response([ 'status' => 'error' ]);
        }

        if (sha1($user->getEmail()) == $emailHash) {
            $user->disabled_emails = true;
            $user->bounced = true;
            $user->save();

            Di::_()->get('Email\Manager')->unsubscribe($user);

            error_log('[AWS-SES] Disabled emails for ' . $guid);
            return Factory::response([]);
        }

        return Factory::response([ 'status' => 'error' ]);
    }

    /**
     * PUT
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * POST
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
