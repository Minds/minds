<?php
/**
 * A minds archive audio entity
 *
 */
namespace Minds\Entities;

use cinemr;
use Minds\Helpers;

// @todo: Check if it's OK we still extend Video
class Audio extends Video
{
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['super_subtype'] = 'archive';
        $this->attributes['subtype'] = "audio";
    }
}
