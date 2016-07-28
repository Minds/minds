<?php
namespace Minds\Core;

/**
 * Minds Multisite Controller
 */
class multisite extends base
{
    protected $domain;

    public function __construct($domain = null)
    {
        global $DOMAIN;

        //load settings, incase we dont have them
        require_once(__MINDS_ROOT__ . '/engine/multi.settings.php');

        if (!$DOMAIN && isset($_SERVER['HTTP_HOST'])) {
            $this->domain = $_SERVER['HTTP_HOST'];
        } elseif ($DOMAIN) {
            $this->domain = $DOMAIN;
        }

        if (strpos($this->domain, ':', 0) !== false) {
            $this->domain = explode(':', $this->domain);
            $this->domain = $this->domain[0];
        }

        $this->host = $this->domain;

        //check if this is asubdomain temp for main domain
        if (strpos($this->domain, '-custdom-001', 0) !== false) {
            $this->domain = str_replace('-custdom-001.minds.com', '', $this->domain);
            $this->domain = str_replace('-', '.', $this->domain);
        }

        if ($this->domain) {
            $this->load($this->domain);
        }
    }

    /**
     * Loads a multisite onto the current environment
     * @param  string $domain
     * @return null
     */
    public function load($domain)
    {
        global $CONFIG;

        if (!$row = $this->getCache($domain)) {
            $db = new Data\Call('domain', $CONFIG->multisite->keyspace, $CONFIG->multisite->servers);
            $row = $db->getRow($domain);
            $this->saveCache($domain, $row);
        }

        if (!(isset($row['installed']) || isset($row['enabled'])) && !defined('__MINDS_INSTALLING__')) {
            header("Location: install.php");
            exit;
        }

        $keyspace = @unserialize($row['keyspace']) ? unserialize($row['keyspace'])  : $row['keyspace'];
        //var_dump($keyspace); exit;
        $CONFIG->cassandra = new \stdClass();
        $CONFIG->cassandra->keyspace = $keyspace;
        $CONFIG->cassandra->servers =  $CONFIG->multisite->servers;

        $CONFIG->wwwroot = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : 'https') . "://$this->host/";
        //if(strpos($CONFIG->wwwroot, '.minds.com') === FALSE)
        //	$CONFIG->wwwroot = "http://$this->host/";

        if (isset($row['dataroot'])) {
            $CONFIG->dataroot = unserialize($row['dataroot']);
        } else {
            $CONFIG->dataroot = "/gluster/data/minds-multisite/".$keyspace;
        }
        $CONFIG->cdn_url = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : 'https') . "://d2ka7pmjfsr8hl.cloudfront.net/$this->host/";
    }

    /**
     * Gets a cached multisite from filestore
     * @param  string $domain
     * @return string|bool
     */
    public function getCache($domain)
    {
        //check the tmp directory to see if there is a cached config of the site
        $path = "/tmp/nodes/$domain";
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }
        return false;
    }

    /**
     * Saves a multisite onto filestore cache
     * @param  string $domain
     * @param  mixed  $data
     * @return null
     */
    public function saveCache($domain, $data)
    {
        $path = "/tmp/nodes/$domain";
        @mkdir('/tmp/nodes/');
        file_put_contents($path, json_encode($data));
    }

    /**
     * Gets a multisite keyspace
     * @param  string $domain
     * @return string|null
     */
    public function getKeyspace($domain = null)
    {
        global $CONFIG;
        $db = new Data\Call('domain', $CONFIG->multisite->keyspace, $CONFIG->multisite->servers);
        $row = $db->getRow($domain);
        return $keyspace = unserialize($row['keyspace']);
    }
}
