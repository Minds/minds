<?php

namespace Minds\Common;

use Minds\Core\Di\Di;
use Minds\Traits\MagicAttributes;

class Cookie
{

    use MagicAttributes;

    /** @var CONFIG $config */
    private $config;

    /** @var string $name */
    private $name;

    /** @var string $value */
    private $value = '';

    /** @var int $expire */
    private $expire = 0;

    /** @var string $path */
    private $path = '';

    /** @var string $domain */
    private $domain = '';

    /** @var bool $secure */
    private $secure = true;

    /** @var bool $httOonly */
    private $httpOnly = true;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * Create the cookie
     * @return void
     */
    public function create()
    {
        if ($this->config->disable_secure_cookies) {
            $this->secure = false;
        }

        if (headers_sent()) {
            return false;
        }

        if (isset($_COOKIE['disable_cookies']) && $this->name != 'disable_cookies') {
            $this->expire = time() - 3600;
            $this->value = '';
        }

        setcookie($this->name, $this->value, $this->expire, $this->path, $this->domain, $this->secure, $this->httpOnly);
        $_COOKIE[$this->name] = $this->value; //set the global cookie
    }

}
