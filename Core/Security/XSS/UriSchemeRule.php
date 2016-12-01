<?php
/**
 * Tags Rule
 */

 namespace Minds\Core\Security\XSS;

use Minds\Interfaces;

class UriSchemeRule implements Interfaces\XSSRule
{
    private $dirtyString = "";
    private $cleanString = "";
    private $allowedSchemes = [];
    private $badSchemes = ['javascript'];

    /**
     * Set the dirty string to sanitize
     * @param $string
     * @return $this
     */
    public function setString($string)
    {
        $this->dirtyString = $string;
        return $this;
    }

    /**
     * Return the clean string
     * @return string
     */
    public function getString()
    {
        return $this->cleanString;
    }

    /**
     * Set the allowed tags or attributes
     * @param array $allowed
     * @return $this
     */
    public function setAllowed($allowed = [])
    {
        $this->allowedSchemes = [];
        foreach ($allowed as $tag) {
            if (strpos($tag, '::') === 0) {
                $this->allowedSchemes[] = $tag;
            }
        }
        return $this;
    }

    /**
     * Clean the dirty string
     * @return $this
     */
    public function clean()
    {
        $this->cleanString = $this->dirtyString;

        foreach ($this->badSchemes as $scheme) {
            if (strpos($this->dirtyString, "$scheme:") !== false) {
                $this->cleanString = $this->dirtyString = str_replace("$scheme:", '', $this->cleanString);

                //run clean agin to prevent issues likes javascriptjavascript::
                $this->clean();
            }
        }

        return $this;
    }
}
