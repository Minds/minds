<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Entities;

class SEO
{
    public function setup()
    {
        Core\SEO\Manager::add('/archive/view', [ $this, 'viewHandler' ]);
        Core\SEO\Manager::add('/media', [ $this, 'viewHandler' ]);
    }

    public function viewHandler($slugs = [])
    {
        $guid = $slugs[0];
        if (isset($slugs[1]) && is_numeric($slugs[1])) {
            $guid = $slugs[1];
        }

        $entity = Entities\Factory::build($guid);
        if (!$entity) {
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
            'og:image:height' => 1000
        ];
    }
}
