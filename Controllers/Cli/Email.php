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

}
