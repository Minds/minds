<?php
/**
 * Reward Claiming Email
 */

namespace Minds\Core\Email\Campaigns;

use Minds\Core;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Core\FounderRewards\FounderRewards;

class Rewards
{
    protected $config;
    protected $founderRewards;

    public function __construct($config = null, $founderRewards = null, Template $template = null, Mailer $mailer = null)
    {
        $this->config = $config ?: Core\Di\Di::_()->get('Config');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->founderRewards = $founderRewards ?: new FounderRewards($this->config);
    }

    public function send()
    {
        $subject = "Your investment reward is now ready";

        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/claim-reward.tpl");

        $queued = 0;
        $skipped = 0;
        $founders = $this->founderRewards->getFounders();

        /*$mock = new \Minds\Core\FounderRewards\Founder();
        $mock->uuid = '99999';
        $mock->name = 'Mark Harding';
        $mock->amount = 1000;
        $mock->email = 'mark@minds.com';
        $founders = [
            $mock
        ];*/

        foreach ($founders as $founder) {

            if (!$founder->uuid|| $founder->sentRewards) {
                $skipped++;
                echo "\r [emails]: $queued queued | $skipped skipped | $founder->name | $founder->uuid";
                continue;
            }

            $queued++;
            $this->template->set('name', $founder->name);
            $this->template->set('email', $founder->email);
            $this->template->set('uuid', $founder->uuid);
            $this->template->set('amount', $founder->amount);

            $this->template->set('validator', sha1($founder->name . $founder->email . $founder->uuid . $founder->amount));
            
            $message = new Message();
            $message->setTo($founder)
                ->setMessageId(implode('-', [$founder->uuid, sha1($founder->name)]))
                ->setSubject($subject)
                ->setHtml($this->template);

            //send email
            //$this->mailer->queue($message);
            echo "\r [emails]: $queued queued | $skipped skipped | $founder->name | $founder->uuid";
        }
        echo "\n [emails]: Completed ($queued queued | $skipped skipped)";
    }

}
