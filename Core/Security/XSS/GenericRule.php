<?php
/**
 * Tags Rule
 */

 namespace Minds\Core\Security\XSS;

use Minds\Interfaces;

class GenericRule implements Interfaces\XSSRule
{
    private $dirtyString = "";
    private $cleanString = "";
    private $allowedAttributes = "";

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
        $this->allowedAttributes = [];
        foreach ($allowed as $tag) {
            if (strpos($tag, '=') === 1) {
                $this->allowedAttributes[] = $tag;
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
        $this->cleanString = preg_replace_callback('%
            (
            <(?=[^a-zA-Z!/])  # a lone <
            |                 # or
            <!--.*?-->        # a comment
            |                 # or
            <[^>]*(>|$)       # a string that starts with a <, up until the > or the end of the string
            |                 # or
            >                 # just a >
            )%x',
            [$this, 'cleanSplit'],
            $this->dirtyString);

        $this->cleanString  = $this->cleanAttributes($this->cleanString);


          //remove trailing line
          if ($this->cleanString && strpos($this->cleanString, "\n", strlen($this->cleanString)-2) !== false && strpos($this->dirtyString, "\n", strlen($this->dirtyString)-2) === false) {
              $this->cleanString = substr($this->cleanString, 0, strlen($this->cleanString)-1);
          }

        return $this;
    }

    private function cleanSplit($matches = [])
    {
        $string = $matches[1];

        if (substr($string, 0, 1) != '<') {
            // We matched a lone ">" character.
            return '&gt;';
        } elseif (strlen($string) == 1) {
            // We matched a lone "<" character.
            return '&lt;';
        }

        if (!preg_match('%^<\s*(/\s*)?([a-zA-Z0-9\-]+)([^>]*)>?|(<!--.*?-->)$%', $string, $matches)) {
            // Seriously malformed.
          return '';
        }

        $slash = trim($matches[1]);
        $element = $matches[2];
        $attributes = $matches[3];
        $comment = isset($matches[4]) ? $matches[4] : false;

        if ($comment) {
            $element = '!--';
        }

        if ($comment) {
            return $comment;
        }

        if ($slash != '') {
            return "</$element>";
        }

        // Is there a closing XHTML slash at the end of the attributes?
        $attributes = preg_replace('%(\s?)/\s*$%', '\1', $attributes, -1, $count);
        $xhtml_slash = $count ? ' /' : '';

        // Clean up attributes.
        //$attr2 = implode(' ', _filter_xss_attributes($attrlist));
        $attr2 = $attributes;
        $attr2 = preg_replace('/[<>]/', '', $attr2);
        $attr2 = strlen($attr2) ? ' ' . $attr2 : '';

        return "<$element$attr2$xhtml_slash>";
    }

    private function cleanAttributes($string)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($string, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        //Evaluate Anchor tag in HTML
        $xpath = new \DOMXPath($dom);
        $elements = $xpath->evaluate("//*");

        foreach ($elements as $element) {

            //check what we are allowed and store an internal pointer
            $safe = [];
            foreach ($this->allowedAttributes as $a) {
                $tag = substr($a, 0, 1); //eg. * is all element, a is just anchor tags (<a>)
                $attr = substr($a, 2);

                if (($tag == '*' || $tag == $element->nodeName) && $element->getAttribute($attr)) {
                    $safe[$attr] = $element->getAttribute($attr);
                }
            }

            while ($element->attributes->length) {
                $element->removeAttribute($element->attributes->item(0)->name);
            }

            foreach ($safe as $k => $v) {
                $element->setAttribute($k, $v);
            }

            //make all urls force open in a new tab/window
            if ($element->nodeName == 'a') {
                $element->setAttribute('target', '_blank');
            }
        }

        return $dom->saveHtml();
    }
}
