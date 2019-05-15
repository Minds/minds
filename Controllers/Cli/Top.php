<?php

namespace Minds\Controllers\Cli;

use Minds\Core\Minds;
use Minds\Cli;
use Minds\Core\Feeds\Top\Manager;
use Minds\Exceptions\CliException;
use Minds\Interfaces;

class Top extends Cli\Controller implements Interfaces\CliControllerInterface
{
    /** @var Manager */
    private $manager;

    public function __construct()
    {
        $minds = new Minds();
        $minds->start();
        $this->manager = new Manager();
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli top sync_<type> --period=? --metric=?');
    }

    public function exec()
    {
        $this->out('Syntax usage: cli top sync_<type> --period=? --metric=?');
    }

    public function sync_activity()
    {
        return $this->syncBy('activity', null, $this->getOpt('period') ?? null, $this->getOpt('metric') ?? null);
    }

    public function sync_images()
    {
        return $this->syncBy('object', 'image', $this->getOpt('period') ?? null, $this->getOpt('metric') ?? null);
    }

    public function sync_videos()
    {
        return $this->syncBy('object', 'video', $this->getOpt('period') ?? null, $this->getOpt('metric') ?? null);
    }

    public function sync_blogs()
    {
        return $this->syncBy('object', 'blog', $this->getOpt('period') ?? null, $this->getOpt('metric') ?? null);
    }

    public function sync_groups()
    {
        return $this->syncBy('group', null, $this->getOpt('period') ?? null, $this->getOpt('metric') ?? null);
    }

    public function sync_channels()
    {
        return $this->syncBy('user', null, $this->getOpt('period') ?? null, $this->getOpt('metric') ?? null);
    }

    protected function syncBy($type, $subtype, $period, $metric)
    {
        if (!$period) {
            throw new CliException('Missing --period flag');
        }

        if (!$metric) {
            throw new CliException('Missing --metric flag');
        }

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $displayType = trim(implode(':', [$type, $subtype]), ':');

        $this->out("Syncing {$displayType} {$period} -> {$metric}");

        $this->manager
            ->setType($type ?: '')
            ->setSubtype($subtype ?: '')
            ->run([
                'period' => $period,
                'metric' => $metric,
            ]);

        $this->out("\nCompleted syncing '{$displayType}'.");
    }
}
