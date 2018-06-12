<?php

/**
 * Minds Blockchain Config
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core;
use Minds\Core\Di\Di;

class Config
{
    /** @var Core\Config */
    protected $config;

    /** @var string */
    protected $key = '';

    /**
     * Config constructor.
     * @param null $config
     */
    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @param string $key
     * @return Config
     */
    public function setKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Gets the overriden config
     * @return array
     */
    public function get()
    {
        $config = $this->config->get('blockchain') ?: [];
        $override = $this->config->get('blockchain_override') ?: [];

        if ($this->key && isset($override[$this->key])) {
            $config = array_merge($config, $override[$this->key]);
        }

        return $config;
    }
}
