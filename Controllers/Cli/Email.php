<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Entities\User;
use Minds\Core\Email\Campaigns\UserRetention\GoneCold;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeComplete;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeIncomplete;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Suggestions\Manager;
use Minds\Core\Di\Di;

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

    public function testGoneCold()
    {
        $userguid = $this->getOpt('guid');
        $output = $this->getOpt('output');
        $send = $this->getOpt('send');
        $user = new User($userguid);

        if (!$user->guid) {
            $this->out('User not found');
            exit;
        }

        $manager = Di::_()->get('Suggestions\Manager');
        $manager->setUser($user);
        $suggestions = $manager->getList();

        $campaign = (new GoneCold())
            ->setUser($user)
            ->setSuggestions($suggestions);

        $message = $campaign->build();

        if ($send) {
            $campaign->send();
        }

        if ($output) {
            file_put_contents($output, $message->buildHtml());
        } else {
            $this->out($message->buildHtml());
        }
    }

    public function testWelcomeComplete()
    {
        $userguid = $this->getOpt('guid');
        $output = $this->getOpt('output');
        $send = $this->getOpt('send');
        $user = new User($userguid);

        if (!$user->guid) {
            $this->out('User not found');
            exit;
        }

        $manager = new Manager();
        $manager2 = new Manager();

        $manager->setUser($user);
        $suggestions = $manager->getList();

        $campaign = (new WelcomeComplete())
            ->setUser($user)
            ->setSuggestions($suggestions);

        $message = $campaign->build();

        if ($send) {
            $campaign->send();
        }

        if ($output) {
            file_put_contents($output, $message->buildHtml());
        } else {
            $this->out($message->buildHtml());
        }
    }

    public function testWelcomeIncomplete()
    {
        $userguid = $this->getOpt('guid');
        $output = $this->getOpt('output');
        $send = $this->getOpt('send');
        $user = new User($userguid);

        if (!$user->guid) {
            $this->out('User not found');
            exit;
        }

        $campaign = (new WelcomeIncomplete())
            ->setUser($user);

        $message = $campaign->build();

        if ($send) {
            $campaign->send();
        }

        if ($output) {
            file_put_contents($output, $message->buildHtml());
        } else {
            $this->out($message->buildHtml());
        }
    }

    public function testWelcomeUserEvent()
    {
        $userguid = $this->getOpt('guid');
        Dispatcher::trigger('welcome_email', 'all', ['user_guid' => $userguid]);
    }
}
