<?php

namespace Minds\Core\Blogs;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;

class SEO
{
    /** @var Manager */
    protected $manager;

    public function __construct(
        $manager = null
    )
    {
        $this->manager = $manager ?: new Manager();
    }

    public function setup()
    {
        Core\SEO\Manager::add('/blog/view', [$this, 'viewHandler']);

        Core\SEO\Manager::add('/blog/featured', function ($slugs = []) {
            return $meta = [
                'title' => 'Featured Blogs',
                'description' => 'List of featured blogs',
                'og:title' => 'Featured Blogs',
                'og:description' => 'List of featured blogs',
                'og:url' => Core\Di\Di::_()->get('Config')->site_url . 'blog/featured',
                'og:image' => Core\Di\Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                'og:image:width' => 1024,
                'og:image:height' => 681
            ];
        });

        Core\SEO\Manager::add('/blog/top', function ($slugs = []) {
            return $meta = [
                'title' => 'Top Blogs',
                'description' => 'List of top blogs',
                'og:title' => 'Top Blogs',
                'og:description' => 'List of top blogs',
                'og:url' => Core\Di\Di::_()->get('Config')->site_url . 'blog/top',
                'og:image' => Core\Di\Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                'og:image:width' => 1024,
                'og:image:height' => 681
            ];
        });
        Core\SEO\Manager::add('/blog/network', function ($slugs = []) {
            return $meta = [
                'title' => 'Blogs from your Network',
                'description' => "Blogs from channels you're subscribed to",
                'og:title' => 'Blogs from your Network',
                'og:description' => "Blogs from channels you're subscribed to",
                'og:url' => Core\Di\Di::_()->get('Config')->site_url . 'blog/network',
                'og:image' => Core\Di\Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                'og:image:width' => 1024,
                'og:image:height' => 681
            ];
        });
        Core\SEO\Manager::add('/blog/my', function ($slugs = []) {
            return $meta = [
                'title' => 'Your Blogs',
                'description' => 'List of your blogs',
                'og:title' => 'Your Blogs',
                'og:description' => 'List of your blogs',
                'og:url' => Core\Di\Di::_()->get('Config')->site_url . 'blog/my',
                'og:image' => Core\Di\Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                'og:image:width' => 1024,
                'og:image:height' => 681
            ];
        });

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

        $blog = $this->manager->get($guid);
        if (!$blog || !$blog->getTitle() || Helpers\Flags::shouldFail($blog) || !Core\Security\ACL::_()->read($blog)) {
            header("HTTP/1.0 404 Not Found");
            return [
                'robots' => 'noindex'
            ];
        }

        $body = strip_tags($blog->getBody());

        if (strlen($body) > 140) {
            $body = substr($body, 0, 139) . '…';
        }

        $url = $blog->getPermaURL();
        $ssl = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');

        if ($ssl) {
            $url = str_replace('http://', 'https://', $url);
        }

        $custom_meta = $blog->getCustomMeta();

        return $meta = array(
            'title' => $custom_meta['title'] ?: $blog->getTitle(),
            'description' => $custom_meta['description'] ?: $body,
            'author' => $custom_meta['author'] ?: '@' . $blog->getOwnerObj()['username'],
            'og:title' => $custom_meta['title'] ?: $blog->getTitle(),
            'og:description' => $custom_meta['description'] ?: $body,
            'og:url' => $url,
            'og:type' => 'article',
            'og:image' => $blog->getIconUrl(800),
            'og:image:width' => 2000,
            'og:image:height' => 1000,
            'al:ios:url' => 'minds://blog/view/' . $blog->getGuid(),
            'al:android:url' => 'minds://blog/view/' . $blog->getGuid(),
            'robots' => $blog->getRating() == 1 ? 'all' : 'noindex',
        );
    }
}
