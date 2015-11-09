<?php
/**
 * Page entity
 */
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;

class Page extends DenormalizedEntity
{

    protected $title;
    protected $body;
    protected $path;
    protected $menuContainer;
    protected $rowKey = 'pages';
    protected $exportableDefaults = [ 'title', 'body', 'path', 'menuContainer' ];

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setPath($path)
    {
        $this->path = $path;
        $this->guid = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setMenuContainer($container){
        $this->menuContainer = $container;
        return $this;
    }

    public function getMenuContainer(){
        return $this->getMenuContainer();
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
            'menuContainer' => $this->menuContainer
        ]);
        if(!$success)
            throw new \Exception("We couldn't save the entity to the database");
        //$this->saveToIndex();
        return $this;
    }

}
