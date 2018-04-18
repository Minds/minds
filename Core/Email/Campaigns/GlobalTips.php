<?php
/**
 * Email campaign for global tips
 */

namespace Minds\Core\Email\Campaigns;


use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

class GlobalTips extends EmailCampaign
{
    protected $template;
    protected $mailer;
    protected $type;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'global';
        $this->topic = 'minds_tips';
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/tip-{$this->type}.tpl");

        $this->template->set('user', $this->user);
        $this->template->set('guid', $this->user->guid);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $subject = "";

        switch ($this->type) {
            case 'rewards':
                $subject = "Earn tokens for contributing";
                break;
            case 'boost':
                $subject = "Gain more views with Boost";
                break;
            case 'wire':
                $subject = "Support other channels with Wire";
                break;
        }

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
