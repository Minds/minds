<?php
/**
 * @author Marcelo
 */

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Data\Call;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

class WirePayment
{
    protected $db;
    protected $template;
    protected $mailer;

    protected $bankAccount;
    protected $dateOfDispatch;
    protected $user;
    protected $description;
    protected $amount;
    protected $charged = false;

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    /**
     * @param $bankAccount
     * @return $this
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

    /**
     * @param $dateOfDispatch
     * @return $this
     */
    public function setDateOfDispatch($dateOfDispatch)
    {
        $this->dateOfDispatch = $dateOfDispatch;
        return $this;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param bool $charged
     * @return $this
     */
    public function setCharged(bool $charged)
    {
        $this->charged = $charged;
        return $this;
    }

    public function send()
    {
        if (!$this->user || !$this->receiptNumber || !$this->receiptData) {
            return;
        }

        $this->template->setTemplate('default.tpl');

        if ($this->charged) {
            $this->template->setBody("./Templates/payout.tpl");

            $subject = 'You have received a payout on Minds';
            $this->template->set('user', $this->user);
            $this->template->set('subject', $subject);
            $this->template->set('bankAccount', $this->bankAccount);
            $this->template->set('dateOfDispatch', $this->dateOfDispatch);
        } else {
            $this->template->setBody("./Templates/payment.tpl");

            $subject = 'You have received a payment on Minds';
            $this->template->set('user', $this->user);
            $this->template->set('subject', $subject);
            $this->template->set('description', $this->description);
            $this->template->set('amount', $this->amount);
        }

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->template . time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->queue($message);
    }

}