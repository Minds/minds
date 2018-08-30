<?php
/**
 * Rate limit scanner
 */
namespace Minds\Core\Security\RateLimits;

use Minds\Entities\User;

class Scanner
{
    /** @var Manager $manager */
    private $manager;

    /** @var Maps $maps */
    private $maps;

    public function __construct($manager = null, $maps = null)
    {
        $this->manager = $manager ?: new Manager;
        $this->maps = $maps ?: Maps::$maps;
    }

    public function run()
    {
        foreach ($this->maps as $key => $opts) {
            $guids = $this->getExceedingGuids($opts);

            foreach ($guids as $guid) {
                $user = new User($guid);

                if (!$user) {
                    continue; //something weird happened
                }

                $this->manager
                    ->setKey($key)
                    ->setUser($user)
                    ->setLimitLength($opts['period']);

                if ($this->manager->isLimited()) {
                    continue; //already limited
                }

                $this->manager->impose();
            }

        }
    }

    public function getExceedingGuids($opts = [])
    {
        $guids = [];

        foreach ($opts['aggregates'] as $agg) {
            $class = is_string($agg) ? new $agg : $agg;
            $results = $class
                ->setFrom((time() - $opts['period']) * 1000)
                ->setTo(time() * 1000)
                ->get();

            foreach ($results as $guid => $count) {
                if ($count >= $opts['threshold']) {
                    $guids[] = $guid;
                }
            }
        }
        return $guids;
    }

}
