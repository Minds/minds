<?php
namespace Minds\Core\ThirdPartyNetworks;

use Minds\Core\Di\Di;

class Manager
{
    protected $networks = [
        'facebook',
        'twitter'
    ];

    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * Returns an array of the available third party networks settings
     * @return array
     */
    public function status()
    {
        $status = [];
        $networks = $this->availableNetworks();

        foreach ($networks as $network => $enabled) {
            if ($enabled) {
                $handler = Factory::build($network);

                $handler->getApiCredentials();
                $status[$network] = $handler->export(); 
            } else {
                $status[$network] = false;
            }
        }

        return $status;
    }

    /**
     * Returns an array of the available third party networks
     * @return array
     */
    public function availableNetworks()
    {
        $availableNetworks = []; 

        foreach ($this->networks as $network) {
            $availableNetworks[$network] = !!$this->config->get($network);
        }

        return $availableNetworks;
    }
}
