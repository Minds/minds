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

        foreach($this->getUsers() as $user){
          $this->template->set('username', $user->username);
          $this->template->set('email', $user->getEmail());
          $this->template->set('guid', $user->guid);
          $this->template->set('user', $user);

          $message = new Message();
          $message->setTo($user)
            ->setSubject("Top 10 featured channels. Open me for a 100 point reward!")
            ->setHtml($this->template);

          //send email
          $this->mailer->queue($message);
        }
    }

    protected function getUsers(){
        //@todo implement an iterator to do this.. a lttle messy
        $timestamps = array_reverse(Timestamps::span(30, 'day'));
        echo date('d-m-Y', $timestamps[$this->period]) . "\n";
        $guids = $this->db->getRow("analytics:signup:day:{$timestamps[$this->period]}", ['limit'=>10000]);
        if(empty($guids)){
          return [];
        }
        $users = Entities::get(['guids' => array_keys($guids)]);
        return $users;
    }

}
