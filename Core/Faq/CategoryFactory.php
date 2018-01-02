<?php
/**
 * FAQ Category Factory
 */
namespace Minds\Core\Faq;

class CategoryFactory
{

    static protected $categories = [];

    static public function _($id)
    {
        $id = strtolower($id);
        if (isset(static::$categories[$id])) {
            return static::$categories[$id];
        }

        $category = new Category();
        $category->setCategory($id);

        return static::$categories[$id] = $category;
    }

}