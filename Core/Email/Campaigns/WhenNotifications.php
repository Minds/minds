<?php


namespace Minds\Core\Email\Campaigns;


use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

class WhenNotifications extends EmailCampaign
{
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

    /**
     * Sets the amount of new notifications
     * @param mixed $amount
     * @return WhenNotifications
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/unread-notifications.tpl");

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $this->template->set('amount', $this->amount);

        $subject = "You have {$this->amount} new unread notifications";

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign . $this->topic . time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->queue($message);
    }

}