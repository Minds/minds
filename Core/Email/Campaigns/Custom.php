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

use Minds\Core\Analytics\Iterators;

class Custom
{
    protected $db;
    protected $template;
    protected $mailer;

    protected $user;
    protected $subject = "";
    protected $templateKey = "";
    protected $topic = "";
    protected $campaign = "";

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setTemplate($template)
    {
        $this->templateKey = $template;
        return $this;
    }

    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
        return $this;
    }

    public function setVars($vars)
    {
        $this->vars = $vars;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/$this->templateKey.tpl");
        $this->template->toggleMarkdown(true);

        $validatorHash = sha1($this->campaign . $user->guid . Config::_()->get('emails_secret'));

        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->guid);
        $this->template->set('user', $this->user);
        $this->template->set('topic', $this->topic);
        $this->template->set('campaign', $this->campaign);
        $this->template->set('validator', $validatorHash);

        foreach ($this->vars as $key => $var) {
            $this->template->set($key, $var);
        }

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-', [ $this->user->guid, sha1($this->user->getEmail()), $validatorHash ]))
            ->setSubject($this->subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->send($message);
    }

}
