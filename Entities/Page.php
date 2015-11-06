<?php
/**
 * Page entity
 */
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;

class Page extends NormalizedEntity
{

    private $title;
    private $body;
    private $path;

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
        return $this;
    }

    public function getPath()
    {
        return $this->path;
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
            'path' => $this->path
        ]);
        if(!$success)
            throw new \Exception("We could save the entity to the database");
        //$this->saveToIndex();
        return $this;
    }

}
