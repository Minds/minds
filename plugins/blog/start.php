<?php
/**
 * Blogs
 */

namespace minds\plugin\blog;

use Minds\Components;
use Minds\Core;
use Minds\Api;

class start extends Components\Plugin
{
    public function __construct()
    {
        Api\Routes::add('v1/blog', '\\minds\\plugin\\blog\\api\\v1\\blog');
        Api\Routes::add('v1/blog/header', '\\minds\\plugin\\blog\\api\\v1\\header');

        Core\SEO\Manager::add('/blog/view', function ($slugs = array()) {
            $guid = $slugs[0];
            if (strlen($guid) < 10) {
                $guid = (new \GUID())->migrate($guid);
            }
            $blog = new entities\Blog($guid);
            if (!$blog->title) {
                return array();
            }

            $description = $blog->description;

            if (strlen($description) > 140) {
                $description = substr($description,0,137) . "...";
            }

            return $meta = array(
                'title' => $blog->title,
                'description' => htmlspecialchars(strip_tags($description)),
                'og:title' => $blog->title,
                'og:description' => htmlspecialchars(strip_tags($description)),
                'og:url' => str_replace('http://', 'https://', $blog->getPermaUrl()),
                'og:type' => 'article',
                'og:image' => $blog->getIconUrl(800),
                'og:image:width' => 2000,
                'og:image:height' => 1000
            );
        });

        //@todo update this to OOP
        \elgg_register_plugin_hook_handler('entities_class_loader', 'all', function ($hook, $type, $return, $row) {
            if ($row->type == 'object' && $row->subtype == 'blog') {
                return new entities\Blog($row);
            }
        });

        $add_link = new Core\Navigation\Item();
        $add_link
            ->setPriority(1)
            ->setIcon('add')
            ->setName('Compose')
            ->setTitle('Compose')
            ->setPath('blog/edit/new')
            ->setVisibility(0); //only show for loggedin
        $featured_link = new Core\Navigation\Item();
        $featured_link
            ->setPriority(2)
            ->setIcon('star')
            ->setName('Featured')
            ->setTitle('Featured')
            ->setPath('blog/featured');
        $trending_link = new Core\Navigation\Item();
        $trending_link
            ->setPriority(3)
            ->setIcon('trending_up')
            ->setName('Trending')
            ->setTitle('Trending')
            ->setPath('blog/trending');
        $my_link = new Core\Navigation\Item();
        $my_link
            ->setPriority(4)
            ->setIcon('person_pin')
            ->setName('My Blogs')
            ->setTitle('My Blogs')
            ->setPath('blog/owner')
            ->setVisibility(0); //only show for loggedin

        $link = new Core\Navigation\Item();
        Core\Navigation\Manager::add($link
            ->setPriority(4)
            ->setIcon('subject')
            ->setName('Blogs')
            ->setTitle('Blogs')
            ->setPath('blog/trending')
            ->addSubItem($add_link)
            ->addSubItem($featured_link)
            ->addSubItem($trending_link)
            ->addSubItem($my_link)
        );
    }
}
