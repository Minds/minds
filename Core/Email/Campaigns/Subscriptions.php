<?php
/**
 * Subscriptions Campaign Emails
 */
namespace Minds\Core\Email\Campaigns;

use Minds\Core\Config;
use Minds\Core\Entities;
use Minds\Entities\User;
use Minds\Core\Data;
use Minds\Core\Analytics\Timestamps;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;

use Minds\Core\Analytics\Iterators;

class Subscriptions
{
    protected $db;
    protected $template;
    protected $mailer;

    protected $dryRun = false;
    protected $subject = "";
    protected $templateKey = "";
    protected $campaign = "";
    protected $offset = "";

    protected $users_map = [
        '607668752611287060' => [ 
            'username' => '@Sargon_of_Akaad',
            'posts' => [ '790299633131360256' ]
        ],
        '602551056588615697' => [
            'username' => '@DaveCullen',
            'posts' => [ '789529035376955392' ]
        ],
        '626772382194872329' => [
            'username' => '@Timcast',
            'posts' => [ '788878120470974464' ]
        ],
        '691315407809683459' => [ 
            'username' => '@Styxhexenhammer',
            'posts' => [ '789900745391333376' ]
        ],
        '645613692976640017' => [
            'username' => '@PaulJosephWatson',
            'posts' => [ '789914205701033984' ]
        ]
    ];
    protected $subscribers_map = [];

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Data\Cassandra\Client();
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = date('d-m-y', time());
    }

    public function setDryRun($dry)
    {
        $this->dryRun = $dry;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    protected function buildPosts()
    {
        foreach ($this->users_map as $user_guid => $data) {
            $prepared = new Data\Cassandra\Prepared\Custom;
            $prepared->query("SELECT * from friendsof where key=?", [
                (string) $user_guid
            ]);
            
            $rows = $this->db->request($prepared);
            while (true) {
                foreach ($this->db->request($prepared) as $row) {
                    $subscriber_guid = $row['column1'];
                    $this->subscribers_map[$subscriber_guid][$user_guid] = $data;
                }
                if ($rows->isLastPage()) { 
                    break; 
                } 
                $rows = $rows->nextPage();
            }
        }
    }

    protected function buildSubject($users_map)
    {

        $subject = "Check out the latest from ";

        $usernames = array_map(function($data) {
            return $data['username'];
        }, $users_map);

        if (count($usernames) == 1) {
            $username = array_values($usernames[0]);
            return "$subject$username";
        }

        $last = " and " . array_pop($usernames);
        $delimitered = implode(', ', $usernames);
        
        return "$subject $delimitered$last";
    }

    public function send()
    {
    
        //build the list of subscribers and their posts
        $this->buildPosts();

        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/subscriptions.tpl");

        $queued = 0;
        $skipped = 0;

        foreach ($this->subscribers_map as $user_guid => $data) {
            $user = new User($user_guid);

            if (!$user instanceof \Minds\Entities\User || !$user->guid || $user->disabled_emails || $user->enabled != "yes") {
                $skipped++;
                echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
                continue;
            }

            try {
                $subject = $this->buildSubject($data);
            } catch (\Exception $e) {
                $skipped++;
                echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
                continue;
            }

            $this->template->set('posts', $data);

            $queued++;

            $validatorHash = sha1($this->campaign . $user->guid . Config::_()->get('emails_secret'));

            $this->template->set('username', $user->username);
            $this->template->set('email', $user->getEmail());
            $this->template->set('guid', $user->guid);
            $this->template->set('user', $user);
            $this->template->set('campaign', $this->campaign);
            $this->template->set('validator', $validatorHash);

            $message = new Message();
            $message->setTo($user)
              ->setMessageId(implode('-', [ $user->guid, sha1($user->getEmail()), $validatorHash ]))
              ->setSubject($subject)
              ->setHtml($this->template);

            //send email
            if (!$this->dryRun) {
                $this->mailer->queue($message);
            }
            //exit;
            echo "\r [emails]: $queued queued | $skipped skipped | " . date('d-m-Y', $user->time_created) . " | $user->guid ";
        }
        echo "[emails]: Completed ($queued queued | $skipped skipped) \n";
    }

    protected function getUsers()
    {
        //scan all users and return past 30 day period
        $users = new Iterators\SignupsOffsetIterator;
        $users->setOffset($this->offset);
        return $users;
    }
}
