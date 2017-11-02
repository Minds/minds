<?php

/**
 * Features Manager
 *
 * @author emi
 */

namespace Minds\Core\Features;

use Minds\Core\Di\Di;
use Minds\Core\Session;

class Manager
{
    /**
     * Checks if a featured is enabled
     * @param $feature
     * @return bool
     */
    public function has($feature)
    {
        $features = Di::_()->get('Config')->get('features') ?: [];

        if (!isset($features[$feature])) {
            error_log("[Features\Manager] Feature '{$feature}' is not declared. Assuming true.");

            return true;
        }

        if ($features[$feature] === 'admin' && Session::isAdmin()) {
            return true;
        }

        return $features[$feature] === true;
    }

    /**
     * Exports the features array
     * @return array
     */
    public function export()
    {
        return Di::_()->get('Config')->get('features') ?: [];
    }
}
