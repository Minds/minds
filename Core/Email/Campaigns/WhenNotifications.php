<?php

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Traits\MagicAttributes;

class WhenNotifications extends EmailCampaign
{
    use MagicAttributes;
    protected $db;
    protected $template;
    protected $mailer;
    protected $amount;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'when';
        $this->topic = 'unread_notifications';
    }

    public function send()
    {
        $tracking = [
            '__e_ct_guid' => $this->user->getGUID(),
            'campaign' => $this->campaign,
            'topic' => $this->topic,
        ];

        $this->template->setTemplate('default.tpl');

        $this->template->setBody('./Templates/unread-notifications.tpl');

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $this->template->set('amount', $this->amount);
        $this->template->set('tracking', http_build_query($tracking));

        $subject = "You have {$this->amount} notifications";

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign.$this->topic.time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->queue($message);
    }
}
