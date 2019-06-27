<?php

namespace Minds\Controllers\api\v2\admin;

use Minds\Api\Exportable;
use Minds\Api\Factory;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Core;
use Minds\Entities\Activity;

class firehose implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * Gets a list of entities sorted for admin approval.
     *
     * @param array $pages
     *
     * @throws \Exception
     */
    public function get($pages)
    {
        /** @var User $currentUser */
        $currentUser = Core\Session::getLoggedinUser();
        
        $algorithm = $pages[0] ?? null;

        if (!$algorithm) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid algorithm'
            ]);
        }

        $type = '';
        switch ($pages[1]) {
            case 'activities':
                $type = 'activity';
                break;
            case 'images':
                $type = 'object:image';
                break;
            case 'videos':
                $type = 'object:video';
                break;
            case 'blogs':
                $type = 'object:blog';
                break;
        }

        $period = $_GET['period'] ?? '12h';

        if ($algorithm === 'hot') {
            $period = '12h';
        } elseif ($algorithm === 'latest') {
            $period = '1y';
        }

      
        $hashtag = null;
        if (isset($_GET['hashtag'])) {
            $hashtag = strtolower($_GET['hashtag']);
        }

        $all = false;
        if (!$hashtag && isset($_GET['all']) && $_GET['all']) {
            $all = true;
        }

        $opts = [
            'limit' => 12,
            'offset' => 0,
            'type' => $type,
            'algorithm' => $algorithm,
            'period' => $period,
            'sync' => false,
            'single_owner_threshold' => 0,
            'nsfw' => [],
            'moderation_user' => Session::getLoggedinUser(),
            'exclude_moderated' => true
        ];

        if ($hashtag) {
            $opts['hashtags'] = [$hashtag];
            $opts['filter_hashtags'] = true;
        } elseif (isset($_GET['hashtags']) && $_GET['hashtags']) {
            $opts['hashtags'] = explode(',', $_GET['hashtags']);
            $opts['filter_hashtags'] = true;
        } elseif (!$all) {
            /** @var Core\Hashtags\User\Manager $hashtagsManager */
            $hashtagsManager = Di::_()->get('Hashtags\User\Manager');
            $hashtagsManager->setUser(Session::getLoggedInUser());

            $result = $hashtagsManager->get([
                'limit' => 50,
                'trending' => false,
                'defaults' => false,
            ]);

            $opts['hashtags'] = array_column($result ?: [], 'value');
            $opts['filter_hashtags'] = false;
        }

        try {
              /** @var Core\Feeds\Firehose\Manager $manager */
            $manager = Di::_()->get('Feeds\Firehose\Manager');
            $activities = $manager->getList($opts);
        } catch (\Exception $e) {
            error_log($e);
            return Factory::response(['status' => 'error', 'message' => $e->getMessage()]);
        }

        if ($type !== 'activity') {
             /** @var Core\Feeds\Top\Entities $entities */
            $entities = new Core\Feeds\Top\Entities();
            $entities->setActor($currentUser);
            $activities = $activities->map([$entities, 'cast']);
        }

        return Factory::response([
            'status' => 'success',
            'entities' => Exportable::_($activities)
        ]);
    }

    public function post($pages)
    {
        if (!is_numeric($pages[0])) {
            header('X-Minds-Exception: entity guid required');
            http_response_code(400);
            return Factory::response(['status' => 'error', 'message' => 'entity guid required']);
        }
        
        
        /** @var EntitiesBuilder $entitiesBuilder */
        $entitiesBuilder = Di::_()->get('EntitiesBuilder');

        $entity = $entitiesBuilder->single($pages[0]);

        $moderator = Session::getLoggedinUser();
        $manager = Di::_()->get('Feeds\Firehose\Manager');
        
        $manager->save($entity, $moderator);
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
