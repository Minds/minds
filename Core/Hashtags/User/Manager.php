<?php

namespace Minds\Core\Hashtags\User;

use Minds\Core\Config;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Entities\User;

class Manager
{

    /** @var User $user */
    private $user;

    /** @var Repository $repository */
    private $repository;

    /** @var abstractCacher */
    private $cacher;

    /** @var Config $config */
    private $config;

    public function __construct($repo = null, $cacher = null, $config = null)
    {
        $this->repository = $repo ?: new Repository;
        $this->cacher = $cacher ?: Di::_()->get('Cache');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * Set the user
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return user hashtags alongside some suggestions
     * @param array $opts
     * @return array
     */
    public function get($opts)
    {
        $opts = array_merge([
            'limit' => 10,
        ], $opts); // Merge in our defaults

        $opts['user_guid'] = $this->user->getGuid();

        $cacheKey = "user-selected-hashtags:{$this->user->getGuid()}";
        $cached =  $this->cacher->get($cacheKey);

        if ($cached !== false) {
            $selected = json_decode($cached, true);
        } else {
            $selected = $this->repository->getAll($opts);
            $this->cacher->set($cacheKey, json_encode($selected));
        }

        $suggested = $this->config->get('tags');

        $output = [];

        foreach ($selected as $row) {
            $tag = $row['hashtag'];
            $output[$tag] = [
                'selected' => true,
                'value' => $tag,
            ];
        }

        foreach ($suggested as $tag) {
            if (isset($output[$tag])) {
                continue;
            }
            $output[$tag] = [
                'selected' => false,
                'value' => $tag,
            ];
        }


        return array_slice(array_values($output), 0, $opts['limit']);
    }

}
