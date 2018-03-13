<?php

namespace Minds\Core\Email\Campaigns;


use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Entities\User;

class Invoice
{
    protected $db;
    protected $template;
    protected $mailer;

    /**
     * @var User
     */
    protected $user;
    protected $amount;
    protected $description;


    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/invoice.tpl");

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $this->template->set('description', $this->description);
        $this->template->set('amount', $this->amount);

        $subject = 'Thank you for your purchase!';

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1('invoice' . time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->queue($message);
    }

}