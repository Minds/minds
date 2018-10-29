<?php
namespace Minds\Core\Hashtags\User;

use Minds\Core\Di\Di;

class Manager
{

    /** @var User $user **/
    private $user;

    /** @var Repository $repository **/
    private $repository;


    /** @var Config $config **/
    private $config;

    public function __construct($repo = null, $config = null)
    {
        $this->repository = $repo ?: new Repository;
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
        $selected = $this->repository->getAll($opts);


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
