<?php

namespace Minds\Core\Email;

use Minds\Core\Analytics\Metrics\Event;

class RouterHooks
{
    public function __construct($event = null)
    {
        $this->event = $event ?: new Event();
    }

    public function withRouterRequest($request)
    {
        $queryParams = $request->getQueryParams();
        $path = $request->getUri()->getPath();
        $action = 'email:clicks';
        if (strpos($path, '/emails/unsubscribe') !== false) {
            $action = 'email:unsubscribe';
        }
        $platform = isset($queryParams['cb']) ? 'mobile' : 'browser';
        if (isset($queryParams['platform'])) {
            $platform = $queryParams['platform'];
        }
        if (isset($queryParams['__e_ct_guid'])) {
            $userGuid = $queryParams['__e_ct_guid'];
            $emailCampaign = $queryParams['campaign'] ?? 'unknown';
            $emailTopic = $queryParams['topic'] ?? 'unknown';
            $emailState = $queryParams['state'] ?? 'unknown';

            $this->event->setType('action')
                ->setAction($action)
                ->setProduct('platform')
                ->setUserGuid($userGuid)
                ->setPlatform($platform)
                ->setEmailCampaign($emailCampaign)
                ->setEmailTopic($emailTopic)
                ->setEmailState($emailState);

            $this->event->push();
        }
    }
}
