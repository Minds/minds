<?php

namespace Minds\Core\Blogs;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;

class SEO
{
    public function setup()
    {
        Core\SEO\Manager::add('/blog/view', [$this, 'viewHandler']);
    }

    public function viewHandler($slugs = [])
    {
        $guid = $slugs[0];
        if (strlen($guid) < 10) {
            $guid = (new \GUID())->migrate($guid);
        }
        $blog = new Entities\Blog($guid);
        if (!$blog->title || Helpers\Flags::shouldFail($blog) || !Core\Security\ACL::_()->read($blog)) {
            header("HTTP/1.0 404 Not Found");
            return [
                'robots' => 'noindex'
            ];
        }

        if (!Core\Session::isLoggedIn() && !isset($_GET['lite'])) {

            $blog->description = (new Core\Security\XSS())->clean($blog->description);

            $lite = new Lite\View();
            $lite->setBlog($blog);
            return die($lite->render());
        }

        $description = strip_tags($blog->description);

        if (strlen($description) > 140) {
            $description = substr($description, 0, 139) . '…';
        }

        return $meta = array(
            'title' => $blog->title,
            'description' => $description,
            'og:title' => $blog->title,
            'og:description' => $description,
            'og:url' => str_replace('http://', 'https://', $blog->getPermaUrl()),
            'og:type' => 'article',
            'og:image' => $blog->getIconUrl(800),
            'og:image:width' => 2000,
            'og:image:height' => 1000
        );
    }
}
