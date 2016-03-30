<?php
/**
 * Minds FS request interface.
 */
namespace Minds\Interfaces;

interface Flaggable
{
    public function getFlag($flag);
    public function setFlag($flag, $value);
}
