<?php
namespace Minds\Core;

/**
 * Minds Views Handler
 * Wrapper to elgg_view
 * @todo Deprecate
 */
class views extends base
{
    public static $cachables = array();

    /**
     * TBD. Not used.
     * @return null
     */
    public function init()
    {
    }

    /**
     * Load a view
     * @param string $view
     * @param array  $params Optional.
     */
    public static function view($view, $params = array())
    {
        global $CONFIG;

        if (in_array($view, self::$cachables)) {
            $path = $CONFIG->system_cache_path;
            if (!is_dir($path)) {
                mkdir($path, 0700, true);
            }
            $path .= md5($view);

            if (file_exists($path)) {
                return file_get_contents($path);
            } else {
                ob_start();

                echo \elgg_view($view, $params);

                $content = ob_get_contents();
                ob_end_clean();

                file_put_contents($path, $content);
            }
        }

        return \elgg_view($view, $params);
    }

    /**
     * Adds a view to the cache
     * @param  string $view
     * @return null
     */
     public static function cache($view)
     {
        array_push(self::$cachables, $view);
     }
}
