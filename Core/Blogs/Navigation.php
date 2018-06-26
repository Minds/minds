<?php
/**
 * Navigation Manager operations for Groups
 */

namespace Minds\Core\Blogs;

use Minds\Core\Navigation\Item;
use Minds\Core\Navigation\Manager as NavigationManager;

class Navigation
{
    /**
     * Initialize Navigation
     */
    public function setup()
    {
        $add_link = new Item();
        $add_link
            ->setPriority(1)
            ->setIcon('add')
            ->setName('Compose')
            ->setTitle('Compose')
            ->setPath('blog/edit/new')
            ->setVisibility(0); //only show for loggedin
        $featured_link = new Item();
        $featured_link
            ->setPriority(2)
            ->setIcon('star')
            ->setName('Featured')
            ->setTitle('Featured')
            ->setPath('blog/featured');
        $trending_link = new Item();
        $trending_link
            ->setPriority(3)
            ->setIcon('trending_up')
            ->setName('Trending')
            ->setTitle('Trending')
            ->setPath('blog/trending');
        $my_link = new Item();
        $my_link
            ->setPriority(4)
            ->setIcon('person_pin')
            ->setName('My Blogs')
            ->setTitle('My Blogs')
            ->setPath('blog/owner')
            ->setVisibility(0); //only show for loggedin

        $link = new Item();
        NavigationManager::add($link
            ->setPriority(4)
            ->setIcon('subject')
            ->setName('Blogs')
            ->setTitle('Blogs')
            ->setPath('blog')
            ->addSubItem($add_link)
            ->addSubItem($featured_link)
            ->addSubItem($trending_link)
            ->addSubItem($my_link)
        );
    }
}