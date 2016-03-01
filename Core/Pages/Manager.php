<?php
/**
 * Minds Page Manager
 */
namespace Minds\Core\Pages;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;

class Manager
{
    private static $_;
    private $pages = [];

    private $db;
    private $indexes;
    private $lookup;

    public function __construct($db, $lookup)
    {
        $this->db = $db;
        $this->lookup = $lookup;
    }

    /**
     * Load a single page from a path (uri)
     * @param string $uri eg. /terms
     */
    public function getPageFromUri($uri)
    {
        if (isset($this->pages[$uri])) {
            return $this->pages[$uri];
        }
        $page = (new Entities\Page($this->db))
            ->loadFromGuid($uri);
        return $page;
    }

    /**
     * Load the list of pages from the database
     * @return $this
     */
    public function loadPages()
    {
        $row = $this->db->getRow('pages');
        if (!$row) {
            return $this;
        }
        foreach ($row as $column) {
            $page = (new Entities\Page($this->db))
                ->loadFromArray($column);
            $this->pages[$page->getPath()] = $page;
        }
        return $this;
    }

    /**
     * Return a list of pages for a menu container
     * @param string $container
     * @return array
     */
    public function getMenu($container = 'footer')
    {
        $this->loadPages();
        $return = [];
        foreach ($this->pages as $page) {
            if ($page->getMenuContainer() == $container) {
                $return[] = $page;
            }
        }
        return $return;
    }

    /**
     * Return a list of all pages
     * @return array
     */
    public function getPages()
    {
        $this->loadPages();
        return array_values($this->pages);
    }

    /**
     * Build the object
     * @return $this
     */
    public static function _()
    {
        return self::$_ = Di::_()->get('PagesManager');
    }
}
