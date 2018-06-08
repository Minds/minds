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
    protected $subject = 'Someone wired you';
    protected $promotionKey = '';

    public function setPromotionKey($key)
    {
        $this->promotionKey = $key;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

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

        if (!method_exists($this->user, 'getEmail')) {
            return;
        }

        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/wire.tpl");

        $validatorHash = sha1($this->campaign . $this->promotionKey . $this->user->guid . Config::_()->get('emails_secret'));

        $this->template->set('guid', $this->user->guid);
        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);
        $this->template->set('key', $this->promotionKey);

        $contract = $this->method === 'onchain' ? 'wire' : 'offchain:wire';

        $this->template->set('contract', $contract);

        $this->template->set('validator', $validatorHash);

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign . $this->topic . time())]))
            ->setSubject($this->subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->queue($message);
    }

}
