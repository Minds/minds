<?php

namespace Minds\Controllers\api\v2;

use Minds\Api\Factory;
use Minds\Core\Onboarding\Manager;
use Minds\Core\Session;
use Minds\Interfaces;

class onboarding implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     * @throws \Exception
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     * @throws \Exception
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        $key = $pages[0] ?? '';
        $value = $_POST['value'] ?? null;

        /** @var Manager */
        $manager = new Manager();
        $manager->setUser(Session::getLoggedInUser());

        switch ($key) {
            case 'creator_frequency':
                $done = (bool) $manager->setCreatorFrequency($value);
                break;

            case 'onboarding_shown':
                $done = (bool) $manager->setOnboardingShown(true);
                break;

            default:
                $done = false;
        }

        return Factory::response([
            'done' => $done
        ]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
