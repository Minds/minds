<?php
/**
* Navigation Manager operations for Groups
*/
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Navigation\Item;
use Minds\Core\Navigation\Manager;

class Navigation
{
    /**
     * Initialize Navigation
     */
    public function setup()
    {
        $create_link = new Item();
        $create_link
        ->setPriority(1)
        ->setIcon('add')
        ->setName('Create')
        ->setTitle('Create')
        ->setPath('groups/create')
        ->setParams([])
        ->setVisibility(0); //only show for loggedin

        $featured_link = new Item();
        $featured_link
        ->setPriority(2)
        ->setIcon('star')
        ->setName('Featured')
        ->setTitle('Featured')
        ->setPath('groups/featured');

        $my_link = new Item();
        $my_link
        ->setPriority(3)
        ->setIcon('person_pin')
        ->setName('My Groups')
        ->setTitle('My Groups')
        ->setPath('groups/member')
        ->setVisibility(2); //only show for loggedin

        $root_link = new Item();
        $root_link
        ->setPriority(7)
        ->setIcon('group_work')
        ->setName('Groups')
        ->setTitle('Groups')
        ->setPath('groups/featured')
        ->addSubItem($create_link)
        ->addSubItem($featured_link)
        ->addSubItem($my_link);

        Manager::add($root_link);
    }
}
