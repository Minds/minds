<?php


namespace Minds\Core\Email\Campaigns;


use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

class WithImprovementTips extends EmailCampaign
{
    protected $template;
    protected $mailer;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'with';
        $this->topic = 'channel_improvement_tips';
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/channel-improvement-tips.tpl");

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        //$this->template->set('posts', $this->posts);

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $subject = 'Tips on how to improve your channel';

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