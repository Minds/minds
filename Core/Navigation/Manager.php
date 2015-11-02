<?php
/**
 * Minds Navigation Manager
 */
namespace Minds\Core\Navigation;

use Minds\Core;

class Manager
{
    private static $containers = array();

    private static function defaults()
    {
        $newsfeed = new Item();
        $newsfeed->setPriority(1)
            ->setIcon('home')
            ->setName('Newsfeed')
            ->setTitle('Newsfeed')
            ->setPath('/Newsfeed');
        self::add($newsfeed);

        $capture = new Item();
        $capture->setPriority(2)
            ->setIcon('file_upload')
            ->setName('Capture')
            ->setTitle('Capture')
            ->setPath('/Capture');
        self::add($capture);

        $discovery_suggested = new Item();
        $discovery_suggested
            ->setPriority(1)
            ->setIcon('call_split')
            ->setName('Suggested')
            ->setTitle('Suggested (Discovery)')
            ->setPath('/Discovery')
            ->setParams(array(
                'filter' => 'suggested',
                'type' => ''
            ));

        $discovery_trending = new Item();
        $discovery_trending
            ->setPriority(2)
            ->setIcon('trending_up')
            ->setName('Trending')
            ->setTitle('Trending (Discovery)')
            ->setPath('/Discovery')
            ->setParams(array(
                'filter' => 'trending',
                'type' => ''
            ));

        $discovery_featured = new Item();
        $discovery_featured
            ->setPriority(3)
            ->setIcon('star')
            ->setName('Featured')
            ->setTitle('Featured (Discovery)')
            ->setPath('/Discovery')
            ->setParams(array(
                'filter' => 'featured',
                'type' => ''
            ));
        $discovery_my = new Item();
        $discovery_my
            ->setPriority(4)
            ->setIcon('person_pin')
            ->setName('My')
            ->setTitle('My (Discovery)')
            ->setPath('/Discovery')
            ->setParams(array(
                'filter' => 'owner',
                'type' => ''
                ))
            ->setVisibility(0); //only show for loggedin

        $discovery = new Item();
        $discovery->setPriority(3)
            ->setIcon('search')
            ->setName('Discovery')
            ->setTitle('Discovery')
            ->setPath('/Discovery')
            ->setParams(array(
                'filter' => 'featured',
                'type' => ''
            ))
            ->addSubItem($discovery_suggested)
            ->addSubItem($discovery_trending)
            ->addSubItem($discovery_featured)
            ->addSubItem($discovery_my);
        self::add($discovery);

        $admin_boost = new Item();
        $admin_boost
            ->setPriority(1)
            ->setIcon('trending_up')
            ->setName('Boost')
            ->setTitle('Boost (Admin)')
            ->setPath('/Admin')
            ->setParams(array(
                'filter' => 'boosts'
            ));
        $admin_analytics = new Item();
        $admin_analytics
            ->setPriority(2)
            ->setIcon('insert_chart')
            ->setName('Analytics')
            ->setTitle('Analytics')
            ->setPath('/Admin')
            ->setParams(array(
                'filter' => 'analytics'
            ));

        $admin = new Item();
        $admin->setPriority(100)
            ->setIcon('settings_input_component')
            ->setName('Admin')
            ->setTitle('Admin')
            ->setPath('/Admin')
            ->setParams(array(
                'filter' => 'analytics'
            ))
            ->addSubItem($admin_boost)
            ->addSubItem($admin_analytics);
        if (Core\Session::isLoggedIn() && Core\Session::getLoggedinUser()->isAdmin()) {
            self::add($admin);
        }

        self::add((new Item())
            ->setPriority(7)
            ->setIcon('account_balance')
            ->setName('Wallet')
            ->setTitle('Wallet')
            ->setPath('/Wallet')
            ->setExtras(array(
                'counter' => (int) Core\Session::isLoggedIn() ? \Minds\Helpers\Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false) : 0
            )),
            "topbar"
        );
    }

    /**
     * Add an item to the Navigation
     * @param Item $item - the item to add to the navigation
     * @param string $container - the container to add the item to
     * @return void
     */
    public static function add($item, $container = "sidebar")
    {
        if ($item instanceof Item) {
            self::getContainer($container)->add($item);
        }
    }

    /**
     * Indepotent get or create container
     * @param string $container - the name or ID of the container
     * @return Container
     */
    private static function getContainer($container)
    {
        if (!isset(self::$containers[$container])) {
            self::$containers[$container] = new Container();
        }
        return self::$containers[$container];
    }

    /**
     * Return items
     * @param string $container - the container to export
     * @return array
     */
    public static function export($container = null)
    {
        self::defaults();
        $containers = array();

        foreach (self::$containers as $id => $container) {
            $containers[$id] = $container->export();
        }
        return $containers;
    }
}
