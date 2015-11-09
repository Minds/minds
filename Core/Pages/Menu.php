<?php
/**
 * Minds Page Menu Container
 */
namespace Minds\Core\Pages;

use Minds\Core;
use Minds\Core\Navigation;

class Menu
{

    private static $_;
    private $containers = [];

    public function init()
    {
        $footer = Manager::_()->getMenu('footer');

        usort($footer, function($a, $b){
          return strcmp($b->getTitle(), $a->getTitle());
        });

        foreach($footer as $page){
            Navigation\Manager::add(
                (new Navigation\Item())
                    ->setName($page->getTitle())
                    ->setTitle($page->getTitle())
                    ->setPath('/P')
                    ->setParams([
                        'page' => $page->getPath()
                    ]),
                "footer"
            );
        }

    }

    public static function _()
    {
        if(!self::$_)
            self::$_ = new Menu();
        return self::$_;
    }
}
