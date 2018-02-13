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

class PointsSnapshot 
{
    protected $db;
    protected $template;
    protected $mailer;

    protected $subject = "";
    protected $templateKey = "";
    protected $campaign = "";

    protected $period = 10;
    protected $offset = "";
    protected $dryRun = false;

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'points-' . date('d-m-y', time());
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
        $this->template->setBody("./Templates/$this->templateKey.tpl");

        $queued = 0;
        $skipped = 0;
        foreach ($this->getUsers() as $user) {

            if (!$user instanceof \Minds\Entities\User || !$user->guid || $user->disabled_emails || $user->enabled != "yes") {
                $skipped++;
                echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
                continue;
            }

            $queued++;

            $validatorHash = sha1($this->campaign . $user->guid . Config::_()->get('emails_secret'));

            //$points = number_format(Helpers\Counters::get($user->guid, 'points', false) ?:0);
            $this->template->set('points', $user->points_snapshot);

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
              ->setSubject($this->subject)
              ->setHtml($this->template);

            //send email
            if (!$this->dryRun) {
                $this->mailer->queue($message);
            }
            echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
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
            new User('iancrossland'),
            ];
        //$tests = [
        //    new User('mark')
        //];
        foreach ($tests as $user) {
            $user->disabled_emails = false;
        }
        return $tests;*/
        //scan all users and return past 30 day period
        $users = new Iterators\PointsSnapshotIterator;
        $users->setOffset($this->offset);
        return $users;
    }
}

