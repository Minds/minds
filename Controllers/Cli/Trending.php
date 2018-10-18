<?php

namespace Minds\Controllers\Cli;

use DateTime;
use Elasticsearch\ClientBuilder;
use Minds\Cli;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers\Flags;
use Minds\Interfaces;

class Trending extends Cli\Controller implements Interfaces\CliControllerInterface
{
    private static $limit = 72;
    private $start;
    private $elasticsearch;

    public function help($command = null)
    {
        $this->out('Syntax usage: cli trending <type>');
    }

    public function exec()
    {
        $this->out('Syntax usage: cli trending <type>');
    }

    public function sync_all()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->out('Collecting trending items');
        $manager = new Core\Trending\Manager();
        $manager->run('all');
        $this->out('Completed syncing all');
    }

    public function sync_newsfeed()
    {
        $this->out('Syncing newsfeed');
        $manager = new Core\Trending\Manager();
        $manager->run('newsfeed');
        $this->out('Completed syncing newsfeed');
    }

    public function sync_images()
    {
        $this->out('Syncing images');
        $manager = new Core\Trending\Manager();
        $manager->run('images');
        $this->out('Completed syncing images');
    }

    public function sync_videos()
    {
        $this->out('Syncing videos');
        $manager = new Core\Trending\Manager();
        $manager->run('videos');
        $this->out('Completed syncing videos');
    }

    public function sync_groups()
    {
        $this->out('Syncing groups');
        $manager = new Core\Trending\Manager();
        $manager->run('groups');
        $this->out('Completed syncing groups');
    }

    public function sync_blogs()
    {
        $this->out('Syncing blogs');
        $manager = new Core\Trending\Manager();
        $manager->run('blogs');
        $this->out('Completed syncing blogs');
    }

    public function legacy()
    {
        $hosts = Core\Di\Di::_()->get('Config')->elasticsearch['hosts'];
        $this->elasticsearch = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $this->repository = Di::_()->get('Trending\Repository');
        $span = $this->getOpt('span');
        $this->start = strtotime("-$span minutes");
        $end = new DateTime('now');
        $this->groups();
        $this->out("[complete] \n");
    }

    private function groups()
    {
        $start = new DateTime('1 day ago');
        $end = new DateTime('now');
        $pattern = '^\/groups\/profile\/([0-9]+)';
        list($trendingUrls, $nextPage) = Di::_()->get('Trending\Services\GoogleAnalytics')
            ->getByPageViews($pattern, $start, $end, '', (int) static::$limit * 1.5);
        $guids = array_values(array_unique(array_map(function ($item) use ($pattern) {
            preg_match("/$pattern/", $item['url'], $matches);
            return $matches[1];
        }, $trendingUrls)));
        $guids = array_slice($guids, 0, static::$limit);
        $guids = array_values(array_filter($guids, function ($guid) {
            $entity = Entities\Factory::build($guid);
            return $entity && !Flags::isDeleted($entity);
        }));
        Di::_()->get('Trending\Repository')->add('group', $guids);
        $this->out("\nCollected " . count($guids) . ' groups');
    }
}