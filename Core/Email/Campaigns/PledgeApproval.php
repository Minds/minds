<?php

/**
 * Minds Pledge Approval Email
 *
 * @author emi
 */

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Entities\User;

class PledgeApproval
{
    /** @var Template */
    protected $template;

    /** @var Mailer */
    protected $mailer;

    /** @var User */
    protected $user;

    /** @var string */
    protected $message;

    /** @var float */
    protected $amount;

    /** @var bool */
    protected $presale = false;

    public function __construct($template = null, $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    /**
     * @param User $user
     * @return PledgeApproval
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param bool $presale
     * @return PledgeApproval
     */
    public function setPresale($presale)
    {
        $this->presale = $presale;
        return $this;
    }

    /**
     * @param float $amount
     * @return PledgeApproval
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function send()
    {
        $action = $this->presale ? 'pledge' : 'reservation';

        $subject = "Your {$action} has been approved.";

        $this->template
            ->setTemplate('default.tpl')
            ->setBody("./Templates/pledge-approval.tpl")
            ->set('user', $this->user)
            ->set('username', $this->user->username)
            ->set('subject', $subject)
            ->set('isPresale', $this->presale)
            ->set('action', $action)
            ->set('amount', $this->amount);

        $message = new Message();
        $messageId = implode('-', [
            $this->user->guid,
            sha1($this->user->getEmail()),
            sha1(__CLASS__ . time())
        ]);

        $message
            ->setTo($this->user)
            ->setMessageId($messageId)
            ->setSubject($subject)
            ->setHtml($this->template);

        $this->mailer->send($message);
    }
}
