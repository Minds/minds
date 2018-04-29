<?php


namespace Minds\Core\Email\Campaigns;


use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

class MissedSinceLogin extends EmailCampaign
{
    protected $template;
    protected $mailer;

    protected $templateKey;
    protected $subject;
    protected $entities;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'with';
        $this->topic = 'posts_missed_since_login';
    }

    /**
     * @param string $templateKey
     * @return Catchup
     */
    public function setTemplateKey($templateKey)
    {
        $this->templateKey = $templateKey;
        return $this;
    }

    /**
     * @param string $subject
     * @return Catchup
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;

    }

    public function setEntities($entities)
    {
        $this->entities = $entities;
        return $this;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function send()
    {
        if(!$this->templateKey || $this->templateKey == '') {
            throw new \Exception('You must set a templatePath');
        }
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/$this->templateKey.tpl");

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->guid);

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $this->template->set('entities', $this->entities);

        $this->user = new \Minds\Entities\User('jack');
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
