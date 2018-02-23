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
        $campaign_id = $this->getOpt('campaign');
        $dry = $this->getOpt('dry-run') ?: false;
        $offset = $this->getOpt('offset') ?: '';
        $subject = $this->getOpt('subject') ?: '';
        $template = $this->getOpt('template') ?: '';

        $campaign = Core\Email\Campaigns\Factory::build($campaign_id);
        $campaign->setDryRun($dry)
            ->setOffset($offset)
            ->setSubject($subject)
            ->setTemplate($template)
            ->send();

        $this->out('Done.');
    }

    public function topPosts()
    {
        $period = $this->getOpt('period');
        $offset = '';

        if (!$period || $period !== 'periodically' && $period !== 'daily' && $period !== 'weekly') {
            throw new CliException('You must set a correct period (periodically, daily or weekly)');
        }

        $batch = Core\Email\Batches\Factory::build('activity');

        $batch->setPeriod($period)
            ->setOffset($offset)
            ->run();
    }

    public function unreadNotifications()
    {
        $offset = $this->getOpt('offset') ?: '';

        $batch = Core\Email\Batches\Factory::build('notifications');
        $batch->setOffset($offset)
            ->run();
    }

}
