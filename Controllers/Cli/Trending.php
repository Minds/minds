<?php

namespace Minds\Controllers\Cli;

use \DateTime;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Entities;

class Trending extends Cli\Controller implements Interfaces\CliControllerInterface
{
    private static $limit = 72;

    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli trending <type>');
    }
    
    public function exec()
    {
        $this->out('Syntax usage: cli trending <type>');
    }

    public function blogs()
    {
        $start = new DateTime('1 day ago');
        $end = new DateTime('now');

        $pattern = '^\/blog\/view\/([0-9]+)';

        list($trendingUrls, $nextPage) = Di::_()->get('Trending\Services\GoogleAnalytics')
            ->getByPageViews($pattern, $start, $end, '', (int) static::$limit * 1.5);

        $guids = array_values(array_unique(array_map(function($item) use ($pattern) {
            preg_match("/$pattern/", $item['url'], $matches);

            return $matches[1];
        }, $trendingUrls)));

        $guids = array_slice($guids, 0, static::$limit);

        $guids = array_values(array_filter($guids, function ($guid) {
            $entity = Entities\Factory::build($guid);
            return $entity &&
                (!method_exists($entity, 'getSpam') || !$entity->getSpam()) &&
                (!method_exists($entity, 'getDeleted') || !$entity->getDeleted());
        }));

        Di::_()->get('Trending\Repository')->store('blog', $guids);

        $this->out('Collected ' . count($guids) . ' blogs');
    }

    public function groups()
    {
        $start = new DateTime('1 day ago');
        $end = new DateTime('now');

        $pattern = '^\/groups\/profile\/([0-9]+)';

        list($trendingUrls, $nextPage) = Di::_()->get('Trending\Services\GoogleAnalytics')
            ->getByPageViews($pattern, $start, $end, '', (int) static::$limit * 1.5);

        $guids = array_values(array_unique(array_map(function($item) use ($pattern) {
            preg_match("/$pattern/", $item['url'], $matches);

            return $matches[1];
        }, $trendingUrls)));

        $guids = array_slice($guids, 0, static::$limit);

        Di::_()->get('Trending\Repository')->store('group', $guids);

        $this->out('Collected ' . count($guids) . ' groups');
    }
}
