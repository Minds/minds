<?php
/**
 * Default seo listeners
 */

namespace Minds\Core\SEO;

use Minds\Entities;

class Defaults
{
    private static $_;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {

    /**
     * Channel default SEO roots
     */
    Manager::add('/', function ($slugs = array()) {
      if (isset($slugs[0]) && is_string($slugs[0])) {
          $user = new Entities\User($slugs[0]);
          if (!$user->guid) {
              return array();
          }

          return $meta = array(
          'title' => $user->name,
          'description' => "Subscribe to @$user->username on Minds. " . strip_tags($user->briefdescription)
        );
      }
    });
    }

    public static function _()
    {
        if (!self::$_) {
            self::$_ = new Defaults();
        }
        return self::$_;
    }
}
