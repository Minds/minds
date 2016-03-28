<?php
/**
 * Retention Campaign Emails
 */
namespace Minds\Core\Email\Campaigns;

use Minds\Core\Entities;
use Minds\Core\Data\Call;
use Minds\Core\Analytics\Timestamps;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

use Minds\Core\Analytics\Iterators;

class Retention
{

    protected $db;
    protected $template;
    protected $mailer;

    protected $period = 1;

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    public function setPeriod($period = 1)
    {
        if(is_int($period))
          return $this;
        $this->period = $period;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/retention-{$this->period}.tpl");

        $featured_guids = (new Call('entities_by_time'))->getRow("object:blog:featured", ['limit' => 10]);
        $featured = Entities::get(['guids' => $featured_guids]);
        $this->template->set('featured', $featured);

        $queued = 0;
        $skipped = 0;
        foreach($this->getUsers() as $user){

            if(!$user->guid || $user->disabled_emails || $user->enabled != "yes"){
                $skipped++;
                echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
                continue;
            }

            $queued++;
            $this->template->set('username', $user->username);
            $this->template->set('email', $user->getEmail());
            $this->template->set('guid', $user->guid);
            $this->template->set('user', $user);

            $message = new Message();
            $message->setTo($user)
              ->setSubject("Top 10 featured channels. Open me for a 100 point reward!")
              ->setHtml($this->template);

            if($this->period >= 30){
                $message->setSubject("Top 10 blogs on Minds");
            }

            //send email
            $this->mailer->queue($message);

            echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
        }
        echo "[emails]: Completed ($queued queued | $skipped skipped) \n";
    }

    protected function getUsers(){
        if($this->period < 30){
            $users = new Iterators\SignupsIterator;
            $users->setPeriod($this->period);
            return $users;
        } else {
            //scan all users and return past 30 day period
            $users = new Iterators\SignupsOffsetIterator;
            $users->setPeriod($this->period);
            return $users;
        }
    }

}
