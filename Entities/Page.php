<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;

/**
 * Page Entity
 */
class Page extends DenormalizedEntity
{
    protected $title;
    protected $body;
    protected $path;
    protected $menuContainer;
    protected $header;
    protected $headerTop;
    protected $subtype = 'page';
    protected $rowKey = 'pages';
    protected $exportableDefaults = [ 'title', 'body', 'path', 'menuContainer', 'header', 'headerTop', 'subtype' ];

    /**
     * Sets `title`
     * @param  string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Gets `title`
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets `body`
     * @param  string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets `path`
     * @param  string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        $this->guid = $path;
        return $this;
    }

    /**
     * Gets `path`
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets `menuContainer`
     * @param  mixed $container
     * @return $this
     */
    public function setMenuContainer($container)
    {
        $this->menuContainer = $container;
        return $this;
    }

    /**
     * Gets `menuContainer`
     * @return mixed
     */
    public function getMenuContainer()
    {
        return $this->menuContainer;
    }

    /**
     * Sets `header`
     * @param  mixed $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Gets `header`
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Sets `top`
     * @param  int $top
     * @return $this
     */
    public function setHeaderTop($top)
    {
        $this->headerTop = (int) $top;
        return $this;
    }

    /**
     * Gets `headerTop`
     * @return int
     */
    public function getHeaderTop()
    {
        return (int) $this->headerTop;
    }

    /**
     * Sets `subtype`
     * @param  string $subtype
     * @return $this
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
        return $this;
    }

    /**
     * Gets `subtype`
     * @return string
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * Save the entity
     * @param boolean $index
     * @return $this
     */
    public function save($index = true)
    {
        $success = $this->saveToDb([
            'title' => $this->title,
            'body' => $this->body,
            'path' => $this->path,
            'menuContainer' => $this->menuContainer,
            'header' => $this->header,
            'headerTop' => $this->headerTop,
            'subtype' => $this->subtype
        ]);
        if (!$success) {
            throw new \Exception("We couldn't save the entity to the database");
        }
        //$this->saveToIndex();
        return $this;
    }

    /**
     * Export the entity onto an array
     * @param  array $keys
     * @return array
     */
    public function export(array $key = [])
    {
        $export = parent::export();
        $export['body'] = (string) $export['body'];

        return $export;
    }
}
