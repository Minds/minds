<?php
/**
 * XSS Rule
 */

namespace Minds\Interfaces;

interface XSSRule
{

    /**
     * Set the dirty string to sanitize
     * @param $string
     * @return $this
     */
    public function setString($string);

    /**
     * Return the clean string
     * @return string
     */
    public function getString();

    /**
     * Set the allowed tags or attributes
     * @param array $allowed
     * @return $this
     */
    public function setAllowed($allowed = []);

    /**
     * Clean the dirty string
     * @return $this
     */
    public function clean();

}
