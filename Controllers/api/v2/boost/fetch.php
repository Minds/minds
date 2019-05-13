<?php
/**
 * Boost Fetch
 *
 * @version 2
 * @author emi
 *
 */

namespace Minds\Controllers\api\v2\boost;

use Minds\Api\Exportable;
use Minds\Common\Urn;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class fetch implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param array $pages
     * @return mixed|null
     * @throws \Exception
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        /** @var Entities\User $currentUser */
        $currentUser = Core\Session::getLoggedinUser();

        if ($currentUser->disabled_boost && $currentUser->isPlus()) {
            return Factory::response([
                'boosts' => [],
            ]);
        }

        // Parse parameters

        $type = $pages[0] ?? 'newsfeed';
        $limit = abs(intval($_GET['limit'] ?? 2));
        $offset = $_GET['offset'] ?? null;
        $rating = intval($_GET['rating'] ?? $currentUser->getBoostRating());
        $platform = $_GET['platform'] ?? 'other';
        $quality = 0;
        $sync = (bool) ($_GET['sync'] ?? true);

        if ($limit === 0) {
            return Factory::response([
                'boosts' => [],
            ]);
        } elseif ($sync && $limit > 500) {
            $limit = 500;
        } elseif (!$sync && $limit > 50) {
            $limit = 50;
        }

        // Options specific to newly created users (<=1 hour) and iOS users

        if ($platform === 'ios') {
            $rating = 1; // they can only see safe content
            $quality = 90;
        } elseif (time() - $currentUser->getTimeCreated() <= 3600) {
            $rating = 1; // they can only see safe content
            $quality = 75;
        }

        //

        $boosts = [];
        $next = null;

        switch ($type) {
            case 'newsfeed':
                // Newsfeed boosts

                /** @var Core\Boost\Network\Iterator $iterator */
                $iterator = Core\Di\Di::_()->get('Boost\Network\Iterator');
                $iterator
                    ->setLimit($limit)
                    ->setOffset($offset)
                    ->setRating($rating)
                    ->setQuality($quality)
                    ->setType($type)
                    ->setPriority(true)
                    ->setHydrate(!$sync);

                if ($sync) {
                    foreach ($iterator as $boost) {
                            $feedSyncEntity = new Core\Feeds\FeedSyncEntity();
                            $feedSyncEntity
                                ->setGuid((string) $boost->getGuid())
                                ->setOwnerGuid((string) $boost->getOwnerGuid())
                                ->setUrn(new Urn("urn:boost:{$boost->getType()}:{$boost->getGuid()}"));

                            $boosts[] = $feedSyncEntity;
                    }
                } else {
                    $boosts = iterator_to_array($iterator, false);
                }

                $next = $iterator->getOffset();
                break;

            case 'content':
                // TODO: Content boosts
            default:
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Unsupported boost type'
                ]);
        }

        return Factory::response([
            'boosts' => Exportable::_($boosts),
            'load-next' => $next ?: null,
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
