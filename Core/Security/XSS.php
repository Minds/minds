<?php
/**
 * XSS Sanitizer
 */
namespace Minds\Core\Security;

use Minds\Core;
use Minds\Interfaces\XSSRule;

class XSS
{

    private $rules = [];
    private $allowed = [];

    public function __construct(){
        $this->init();
    }

    /**
     * Initialise our basic rules
     * @return void
     */
    private function init()
    {
      $this->setAllowed();
      $this->addRule(new XSS\TagsRule);
      $this->addRule(new XSS\GenericRule);
      $this->addRule(new XSS\UriSchemeRule);
    }

    /**
     * Add rules to check
     * @param XSSRule $rules
     * @return $this
     */
    private function addRule(XSSRule $rule)
    {
      $this->rules[] = $rule;
      return $this;
    }

    /**
     * Set the allowed attributes and tag names
     * @param array $allowed
     * @return $this
     */
    public function setAllowed($allowed = [])
    {
        $this->allowed = array_merge($allowed, [
          '<a>', '<b>', '<strong>', '<ul>', '<li>', '<p>', '<img>', '<video>', '<iframe>', //tag names
          'a=href', '*=src', '*=width', '*=height', //attibute names
          '::http', '::https', '::*', //scheme protocols
        ]);

        return $this;
    }

    /**
     * Clean a html block of possibel XSS tags
     * @param string $string
     * @return string
     */
    public function clean($string)
    {
        foreach($this->rules as $rule){
            $string = $rule
              ->setString($string)
              ->setAllowed($this->allowed)
              ->clean()
              ->getString();
        }

        return $string;

    }

}
