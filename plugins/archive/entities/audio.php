<?php
/**
 * A minds archive audio entity
 *
 */
namespace minds\plugin\archive\entities;

use Minds\Entities\Object;
use cinemr;
use Minds\Helpers;

class audio extends video
{
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['super_subtype'] = 'archive';
        $this->attributes['subtype'] = "audio";
    }
}
