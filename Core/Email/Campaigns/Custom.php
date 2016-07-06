<?php
/**
 * Custom Campaign Emails
 */
namespace Minds\Core\Email\Campaigns;

use Minds\Core\Entities;
use Minds\Core\Data\Call;
use Minds\Core\Analytics\Timestamps;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

use Minds\Core\Analytics\Iterators;

class Custom
{

    protected $db;
    protected $template;
    protected $mailer;

    protected $subject = "";
    protected $templateKey = "";

    protected $period = 1;

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setTemplate($template)
    {
        $this->templateKey = $template;
        return $this;
    }

    public function send()
    {

        $featured_guids = (new Call('entities_by_time'))->getRow("object:blog:featured", ['limit' => 10]);
        $featured = Entities::get(['guids' => $featured_guids]);
        $this->template->set('featured', $featured);

        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/$this->templateKey.tpl");

        $queued = 0;
        $skipped = 0;
        foreach($this->getUsers() as $user){

            $user->email = 'mark@minds.com';

            if(!$user instanceof \Minds\Entities\User || !$user->guid || $user->disabled_emails || $user->enabled != "yes"){
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
              ->setSubject($this->subject)
              ->setHtml($this->template);

            //send email
            $this->mailer->queue($message);

            echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
        }
        echo "[emails]: Completed ($queued queued | $skipped skipped) \n";
    }

    protected function getUsers(){
        //scan all users and return past 30 day period
        $users = new Iterators\SignupsOffsetIterator;
        $users->setPeriod($this->period);
        return $users;
    }

}
