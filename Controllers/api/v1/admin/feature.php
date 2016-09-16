<?php
/**
 * Minds Admin: Feature
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class feature implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     *
     */
    public function get($pages)
    {
        $response = array();
        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response(array());
    }

    /**
     * Feature a post
     * @param array $pages
     */
    public function put($pages)
    {
        $entity = Entities\Factory::build($pages[0]);
        $category = isset($pages[1]) ? $pages[1] : 'other';

        if (!$entity) {
            return Factory::response([
              'status' => 'error',
              'message' => "Entity not found"
            ]);
        }
        if (!$entity->featured_id || $entity->featured_id == 0) {
            $entity->feature();
            $entity->category = $category;

            $repository = Di::_()->get('Categories\Repository');
            $repository->setFilter('featured')
              ->setCategories([$category])
              ->setType($entity->subtype ?: $entity->type)
              ->add($entity->guid);
        } else {
            $entity->unFeature();
            $repository = Di::_()->get('Categories\Repository');
            $repository->setFilter('featured')
              ->setCategories([$entity->category])
              ->setType($entity->subtype ?: $entity->type)
              ->remove($entity->guid);
        }

        $entity->save();

        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        $entity = Entities\Factory::build($pages[0]);
        $category = isset($pages[1]) ? $pages[1] : $entity->category;

        if (!$entity) {
            return Factory::response(array(
          'status' => 'error',
          'message' => "Entity not found"
        ));
        }

        $entity->unFeature();
        $entity->save();

        $repository = Di::_()->get('Categories\Repository');
        $repository->setFilter('featured')
          ->setCategories([$category])
          ->setType($entity->subtype ?: $entity->type)
          ->remove($entity->guid);

        return Factory::response(array());
    }
}
