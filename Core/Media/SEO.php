<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;

class SEO
{
    public function setup()
    {
        Core\SEO\Manager::add('/archive/view', [ $this, 'viewHandler' ]);
        Core\SEO\Manager::add('/media', [ $this, 'viewHandler' ]);
    }

    private function getInfo($title, $description, $url) {
        return [
            'title' => $title,
            'description' => $description,
            'og:title' => $title,
            'og:description' => $description,
            'og:url' => $url,
            'og:image' => Core\Di\Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
            'og:image:width' => 1024,
            'og:image:height' => 681
        ];
    }

    public function viewHandler($slugs = [])
    {
        $allowedSections = ['top', 'network', 'my'];
        if (($slugs[0] === 'images' || $slugs[0] === 'videos') && array_search($slugs[1], $allowedSections) !== false) {
            $type = ucfirst($slugs[0]);
            switch ($slugs[1]) {
                case 'top':
                    return $this->getInfo("Top $type",
                        "$type from channels I'm subscribed to",
                        Core\Di\Di::_()->get('Config')->site_url . implode('/', $slugs));
                    break;
                case 'network':
                    return $this->getInfo("$type from your Network",
                        "$type from channels you're subscribed to",
                        Core\Di\Di::_()->get('Config')->site_url . implode('/', $slugs));
                    break;

                case 'my':
                    return $this->getInfo(
                        "Your $type",
                        "List of your $type",
                        Core\Di\Di::_()->get('Config')->site_url . implode('/', $slugs));
                    break;
            }
        }

        $guid = $slugs[0];
        if (isset($slugs[1]) && is_numeric($slugs[1])) {
            $guid = $slugs[1];
        }

        if ($guid == 'videos' || $guid == 'images') {
            return [];
        }

        $entity = Entities\Factory::build($guid);
        if (!$entity || Helpers\Flags::shouldFail($entity)) {
            header("HTTP/1.0 404 Not Found");
            return [
                'robots' => 'noindex'
            ];
        }

        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'og:title' => $entity->title,
            'og:description' => $entity->description,
            'og:type' => $entity->subtype == 'video' ? 'video' : 'article',
            'og:url' => $entity->perma_url,
            'og:image' => $entity->getIconUrl('xlarge'),
            'og:image:width' => 2000,
            'og:image:height' => 1000,
            'robots' => $entity->getRating == 1 ? 'all' : 'noindex',
        ];
    }
}
