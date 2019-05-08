<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Entities\User;
use Minds\Core\Email\Campaigns\UserRetention\GoneCold;
use Minds\Core\Email\Campaigns\WhenBoost;
use Minds\Core\Email\Campaigns\WireReceived;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeComplete;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeIncomplete;
use Minds\Core\Suggestions\Manager;
use Minds\Core\Analytics\Timestamps;
use Minds\Core\Di\Di;

class Email extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        switch ($command) {
            case 'exec':
                $this->out(file_get_contents(dirname(__FILE__).'/Help/Email/exec.txt'));
                break;
            case 'testWhenBoost':
                $this->out(file_get_contents(dirname(__FILE__).'/Help/Email/testWhenBoost.txt'));
                break;
            case 'testGoneCold':
                $this->out(file_get_contents(dirname(__FILE__).'/Help/Email/testGoneCold.txt'));
                break;
            case 'testWelcomeComplete':
                $this->out(file_get_contents(dirname(__FILE__).'/Help/Email/testWelcomeComplete.txt'));
                break;
            case 'testWelcomeIncomplete':
                $this->out(file_get_contents(dirname(__FILE__).'/Help/Email/testWelcomeIncomplete.txt'));
                break;
            case 'testWireReceived':
                $this->out(file_get_contents(dirname(__FILE__).'/Help/Email/testWireReceived.txt'));
                break;
            default:
                $this->out('Utilities for testing emails and sending them manually');
                $this->out('try `cli Email {command} --help');
                $this->displayCommandHelp();
        }
    }

    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $batch = $this->getOpt('batch');
        $dry = $this->getOpt('dry-run') ?: false;
        $from = (strtotime('midnight', $this->getOpt('from')) ?: Timestamps::get(['day'])['day']);

        $offset = $this->getOpt('offset') ?: '';
        $subject = $this->getOpt('subject') ?: '';
        $template = $this->getOpt('template') ?: '';

        $batchRunner = Core\Email\Batches\Factory::build($batch);

        $batchRunner->setFrom($from)
            ->setDryRun($dry)
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

    public function testWireReceived()
    {
        $output = $this->getOpt('output');
        $entityGuid = $this->getOpt('guid');
        $senderGuid = $this->getOpt('sender');
        $timestamp = $this->getOpt('timestamp');

        $send = $this->getOpt('send');

        $repository = Di::_()->get('Wire\Repository');

        if (!$entityGuid) {
            $this->out('--guid=wire guid required');
            exit;
        }

        if (!$senderGuid) {
            $this->out('--sender=guid required');
            exit;
        }

        if (!timestamp) {
            $this->out('--timestamp=timestamp required');
            exit;
        }

        $wireResults = $repository->getList([
            'entity_guid' => $entityGuid,
            'sender_guid' => $senderGuid,
            'timestamp' => [
                'gte' => $timestamp,
                'lte' => $timestamp,
            ],
        ]);

        if (!$wireResults || count($wireResults['wires']) === 0) {
            $this->out('Wire not found');
            exit;
        }
        $wire = $wireResults['wires'][0];
        $campaign = (new WireReceived())
            ->setUser($wire->getReceiver())
            ->setWire($wire);

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

    public function testWhenBoost()
    {
        $output = $this->getOpt('output');
        $entityGuid = $this->getOpt('guid');
        $boostType = $this->getOpt('type');
        $send = $this->getOpt('send');

        $manager = Di::_()->get('Boost\Network\Manager');

        if (!$entityGuid) {
            $this->out('--guid=boost guid required');
            exit;
        }

        if (!$boostType) {
            $this->out('--type=boost type required');
            exit;
        }

        $boost = $manager->get("urn:boost:{$boostType}:{$entityGuid}", [ 'hydrate' => true ]);

        if (!$boost) {
            $this->out('Boost not found');
            exit;
        }

        $campaign = (new WhenBoost())
            ->setUser($boost->getOwner())
            ->setBoost($boost->export());

        $message = $campaign->build();

        if ($send) {
            Core\Events\Dispatcher::trigger('boost:completed', 'boost', ['boost' => $boost]);
        }

        if ($output) {
            file_put_contents($output, $message->buildHtml());
        } else {
            $this->out($message->buildHtml());
        }
    }
}
