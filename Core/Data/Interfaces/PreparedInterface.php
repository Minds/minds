<?php
/**
 * Prepared data interface
 */

namespace Minds\Core\Data\Interfaces;

interface PreparedInterface
{
    /**
     * Build the prepared request
     * @return array
     */
    public function build();
}
