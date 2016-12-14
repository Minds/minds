<?php
namespace Minds\Entities;

/**
 * Base Entity
 * @todo Do not inherit from ElggEntity
 */
class Entity extends \ElggEntity
{
    protected $exportContext = false;

    public function hasExportContext()
    {
        return $this->exportContext;
    }

    public function setExportContext($exportContext)
    {
        $this->exportContext = $exportContext;
        return $this;
    }
}
