<?php
namespace Minds\Interfaces;

/**
 * Interface for flaggable Entities
 */
interface Flaggable
{
    /**
     * Gets a flag value. Null if not found.
     * @param  string $flag
     * @return mixed|null
     */
    public function getFlag($flag);

    /**
     * Sets a flag value.
     * @param string $flag
     * @param mixed  $value
     */
    public function setFlag($flag, $value);
}
