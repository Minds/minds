<?php
namespace Minds\Core\Email\Campaigns;

use Minds\Core\Config;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

class News extends EmailCampaign
{
    protected $template;
    protected $mailer;

    protected $templateKey;
    protected $subject;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'global';
        $this->topic = 'minds_news';
    }

    /**
     * @param string $templateKey
     * @return Promotion
     */
    public function setTemplateKey($templateKey)
    {
        $this->templateKey = $templateKey;
        return $this;
    }

    /**
     * @param string $subject
     * @return Promotion
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;

    }

    /**
     * @return void
     * @throws \Exception
     */
    public function send()
    {
        if (!method_exists($this->user, 'getEmail')) {
            return;
        }
        if (!$this->templateKey || $this->templateKey == '') {
            throw new \Exception('You must set a templatePath');
        }
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/$this->templateKey.tpl");
        $this->template->toggleMarkdown(true);

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->guid);

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        //do not reward twice
        $validatorHash = sha1($this->campaign . $this->topic . $this->user->guid . Config::_()->get('emails_secret'));
        $this->template->set('validator', $validatorHash);

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign . $this->topic . time())]))
            ->setSubject($this->subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->send($message);
        exit;
    }

}
