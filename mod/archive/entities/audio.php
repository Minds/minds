<?php
/**
 * A minds archive audio entity
 * 
 */
namespace minds\plugin\archive\entities;

use minds\entities\object;
use cinemr;
use Minds\Helpers;

class audio extends video{
    
    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes['super_subtype'] = 'archive';
        $this->attributes['subtype'] = "audio";
    }
}
