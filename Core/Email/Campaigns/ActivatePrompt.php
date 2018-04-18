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

class ActivatePrompt 
{
    protected $db;
    protected $template;
    protected $mailer;

    protected $subject = "";
    protected $templateKey = "active-prompt";
    protected $campaign = "nov-28";

    protected $period = 0;

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
        return $this;
    }
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
        return $this;
    }
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
    public function setTemplate($key)
    {
        $this->templateKey = $key;
        return $this;
    }

    public function send()
    {
        $this->template->set('points', 0);

        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/active-prompt-3.tpl");

        $queued = 0;
        $skipped = 0;
        foreach ($this->getUsers() as $user) {

            //if ($user->guid < 606637640023810056) {
            //    exit;
            //}

            $from = 1470009600;
            $to = 1480291200;
            //$skip = !($user->last_login > $from && $user->last_login < $to);
            $skip = $user->last_login > $to;

            if (!$user instanceof \Minds\Entities\User || !$user->guid || $user->bounced || $user->enabled != "yes" || $skip) {
                $skipped++;
                //echo "\n [emails]: $queued queued | $skipped skipped | JOINED:" . date('d-m-Y', $user->time_created) . " | GUID:$user->guid | LASTACTIVE:" .date('d-m-Y', $user->last_login);
                continue;
            }

            //if($user->last_login > time() - (86400 * 365)){
                

            $queued++;

            $validatorHash = sha1($this->campaign . $user->guid . Config::_()->get('emails_secret'));

            //$points = number_format(Helpers\Counters::get($user->guid, 'points', false) ?:0);
            //$this->template->set('points', $points);

            $this->template->set('username', $user->username);
            $this->template->set('email', $user->getEmail());
            $this->template->set('guid', $user->guid);
            $this->template->set('user', $user);
            $this->template->set('campaign', $this->campaign);
            $this->template->set('validator', $validatorHash);

            $message = new Message();
            $message->setTo($user)
              ->setMessageId(implode('-', [ $user->guid, sha1($user->getEmail()), $validatorHash ]))
              //->setSubject("You have $points points waiting...")
              ->setSubject("Sign-in to keep your username!")
              ->setHtml($this->template);

            //send email
            $this->mailer->queue($message);
            echo "\n [emails]: $queued queued | $skipped skipped | JOINED:" . date('d-m-Y', $user->time_created) . " | GUID:$user->guid | LASTACTIVE:" . date('d-m-Y', $user->last_login);
        }
        echo "[emails]: Completed ($queued queued | $skipped skipped) \n";
    }

    protected function getUsers()
    {
        /*$tests = [
            new User('mark'),
            new User('john'),
            new User('jack'),
            new User('ottman'),
            new User('iancrossland')
            ];
        //$tests = [
        //   new User('mark')
        //];
        foreach ($tests as $k => $user) {
            $tests[$k]->disabled_emails = false;
            $tests[$k]->last_login = 1480009600;
            $tests[$k]->bounced = false;
        }
        return $tests;*/
        //scan all users and return past 30 day period
        $users = new Iterators\SignupsOffsetIterator;
        //$users->setPeriod($this->period);
        $users->setOffset($this->offset);
        return $users;
    }
}
