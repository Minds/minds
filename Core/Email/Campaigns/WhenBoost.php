<?php

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Core\Email\Manager;
use Minds\Core\Di\Di;

class WhenBoost extends EmailCampaign
{
    protected $db;
    protected $template;
    protected $mailer;

    protected $boost;

    public function __construct(Template $template = null, Mailer $mailer = null, Manager $manager = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'when';
        $this->topic = 'boost_completed';
        $this->manager = $manager ?: Di::_()->get('Email\Manager');
    }

    public function setBoost($boost)
    {
        $this->boost = $boost;

        return $this;
    }

    public function build()
    {
        if (!$this->user) {
            return false;
        }

        $tracking = [
            '__e_ct_guid' => $this->user->getGUID(),
            'campaign' => $this->campaign,
            'topic' => $this->topic,
        ];

        $this->template->setTemplate('default.tpl');

        $this->template->setBody('./Templates/boost.tpl');

        $this->template->set('guid', $this->user->guid);
        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('boost', $this->boost);
        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);
        $this->template->set('tracking', http_build_query($tracking));

        $subject = 'Your boost is complete';
        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign.$this->topic.time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        return $message;
    }

    public function send()
    {
        if ($this->canSend()) {
            $this->mailer->queue($this->build());
        }
    }
}
