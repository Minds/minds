<?php


namespace Minds\Core\Email\Campaigns;


use Minds\Core\Config;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

class WhenWire extends EmailCampaign
{
    protected $template;
    protected $mailer;
    protected $method;

    /**
     * @param mixed $method
     * @return WhenWire
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'when';
        $this->topic = 'wire_received';
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/wire.tpl");

        $validatorHash = sha1($this->campaign . $this->user->guid . Config::_()->get('emails_secret'));

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $contract = $this->method === 'onchain' ? 'wire' : 'offchain:wire';

        $this->template->set('contract', $contract);

        $this->template->set('validator', $validatorHash);

        $subject = 'Someone wired you';

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