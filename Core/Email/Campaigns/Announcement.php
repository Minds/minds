<?php
/**
 * Custom Campaign Emails
 */

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Config;
use Minds\Core\Entities;
use Minds\Core\Data\Call;
use Minds\Core\Analytics\Timestamps;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Helpers;
use Minds\Entities\User;
use Minds\Core\Analytics\Iterators;

class Announcement extends EmailCampaign
{
    protected $template;
    protected $mailer;

    protected $subject = "";
    protected $templateKey = "";
    protected $campaign;
    protected $topic;

    protected $period = 10;
    protected $offset = "";
    protected $dryRun = false;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'global';
        $this->topic = 'exclusive_promotions';
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setTemplateKey($key)
    {
        $this->templateKey = $key;
        return $this;
    }

    public function send()
    {
        $this->template->set('points', 0);

        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/$this->templateKey.tpl");

        $validatorHash = sha1($this->campaign . $this->topic . $this->user->guid . Config::_()->get('emails_secret'));

        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->guid);
        $this->template->set('user', $this->user);
        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);
        $this->template->set('validator', $validatorHash);

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-', [$this->user->guid, sha1($this->user->getEmail()), $validatorHash]))
            ->setSubject($this->subject)
            ->setHtml($this->template);
        $this->mailer->queue($message);
    }
}
