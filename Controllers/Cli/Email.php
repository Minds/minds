<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Entities;

class Email extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $batch = $this->getOpt('batch');
        $dry = $this->getOpt('dry-run') ?: false;
        $offset = $this->getOpt('offset') ?: '';
        $subject = $this->getOpt('subject') ?: '';
        $template = $this->getOpt('template') ?: '';

        $campaign = Core\Email\Batches\Factory::build($batch);
        $campaign->setDryRun($dry)
            ->setOffset($offset)
            ->setSubject($subject)
            ->setTemplateKey($template)
            ->run();

        $this->out('Done.');
    }

    public function topPosts()
    {
        $this->out('Top posts');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $period = $this->getOpt('period');
        $offset = '';

        if (!$period || $period !== 'periodically' && $period !== 'daily' && $period !== 'weekly') {
            throw new CliException('You must set a correct period (periodically, daily or weekly)');
        }

        $batch = Core\Email\Batches\Factory::build('activity');

        $batch->setPeriod($period)
            ->setOffset($offset)
            ->run();
        $this->out('done');
    }

    public function RetentionTips()
    {
        $this->out('Retention emails');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $period = $this->getOpt('period');
        $offset = '';

        $batch = Core\Email\Batches\Factory::build('RetentionTips');

        $batch->setPeriod($period)
            ->setOffset($offset)
            ->run();
        $this->out('done');
    }

    public function unreadNotifications()
    {
        $offset = $this->getOpt('offset') ?: '';

        $batch = Core\Email\Batches\Factory::build('notifications');
        $batch->setOffset($offset)
            ->run();
    }

    public function testWelcome()
    {
        $user = new Entities\User('mark');
        $template = new Core\Email\Template();
        $template
          ->setTemplate()
          ->setBody('welcome.tpl')
          ->set('guid', $user->guid)
          ->set('username', $user->username)
          ->set('email', $user->getEmail())
          ->set('user', $user);
        $message = new Core\Email\Message();
        $message->setTo($user)
          ->setMessageId(implode('-', [ $user->guid, sha1($user->getEmail()), sha1('register-' . time()) ]))
          ->setSubject("Welcome to Minds. Introduce yourself.")
          ->setHtml($template);
        $mailer = new Core\Email\Mailer();
        $mailer->send($message);
    }

}
