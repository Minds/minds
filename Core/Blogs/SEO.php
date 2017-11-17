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

        Core\Events\Dispatcher::register('seo:route', '/', function (Core\Events\Event $event) {
            $params = $event->getParameters();
            $slugs = $params['slugs'];

            if ((count($slugs) < 3) || ($slugs[1] != 'blog')) {
                return;
            }

            $slugParts = explode('-', $slugs[2]);
            $guid = $slugParts[count($slugParts) - 1];

            if (!is_numeric($guid)) {
                return;
            }

            $event->setResponse($this->viewHandler([ $guid ]));
        });
    }

    public function viewHandler($slugs = [])
    {
        if (!is_numeric($slugs[0]) && isset($slugs[1]) && is_numeric($slugs[1])) {
            $guid = $slugs[1];
        } else {
            $guid = $slugs[0];
        }

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

        /*if (!Core\Session::isLoggedIn() && !isset($_GET['lite'])) {

            $blog->description = (new Core\Security\XSS())->clean($blog->description);

            $lite = new Lite\View();
            $lite->setBlog($blog);
            return die($lite->render());
        }*/

        $description = strip_tags($blog->description);

        if (strlen($description) > 140) {
            $description = substr($description, 0, 139) . '…';
        }

        $url = $blog->getPermaURL();
        $ssl = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');

        if ($ssl) {
            $url = str_replace('http://', 'https://', $url);
        }

        $custom_meta = $blog->getCustomMeta();

        return $meta = array(
            'title' => $custom_meta['title'] ?: $blog->title,
            'description' => $custom_meta['description'] ?: $description,
            'author' => $custom_meta['author'] ?: '@' . $blog->getOwnerEntity()->username,
            'og:title' => $custom_meta['title'] ?: $blog->title,
            'og:description' => $custom_meta['description'] ?: $description,
            'og:url' => $url,
            'og:type' => 'article',
            'og:image' => $blog->getIconUrl(800),
            'og:image:width' => 2000,
            'og:image:height' => 1000
        );
    }
}
