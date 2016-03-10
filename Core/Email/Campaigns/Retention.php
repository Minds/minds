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

class Retention
{

    protected $db;
    protected $template;
    protected $message;
    protected $mailer;

    protected $period = 1;

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->message = $message ?: new Message();
        $this->mailer = $mailer ?: new Mailer();
    }

    public function setPeriod($period = 1)
    {
        $allowed = [1,3,7,28];
        if(in_array($period, $allowed)){
          $this->period = $period;
        }
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/retention-{$this->period}.tpl");

        foreach($this->getUsers() as $user){
          $this->template->set('username', $user->username);
          $this->template->set('email', $user->getEmail());
          $this->template->set('guid', $user->guid);
          $this->template->set('user', $user);

          $this->message = new Message();
          $this->message->setTo($user);
          $this->message->setHtml($this->template);

          //send email
          $this->mailer->send($this->message);
        }
    }

    protected function getUsers(){
        //@todo implement an iterator to do this.. a lttle messy
        $timestamps = array_reverse(Timestamps::span(30, 'day'));
        $guids = $this->db->getRow("analytics:signup:day:{$timestamps[$this->period]}", ['limit'=>10000]);
        if(empty($guids)){
          return [];
        }
        $users = Entities::get(['guids' => array_keys($guids)]);
        return $users;
    }

}
