<?php
/**
 * Tags Rule
 */

 namespace Minds\Core\Security\XSS;

use Minds\Interfaces;

class TagsRule implements Interfaces\XSSRule
{
    private $dirtyString = "";
    private $cleanString = "";
    private $allowedTags = "";

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
        $this->allowedTags = "";
        foreach ($allowed as $tag) {
            if (strpos($tag, '<') === 0) {
                $this->allowedTags .= $tag;
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
        $this->cleanString = strip_tags($this->dirtyString, $this->allowedTags);
        return $this;
    }
}
