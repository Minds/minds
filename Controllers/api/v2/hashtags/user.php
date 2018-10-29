<?php

namespace Minds\Controllers\api\v2\hashtags;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;

class user implements Interfaces\Api
{
    public function get($pages)
    {
        Factory::isLoggedIn();


        /** @var Core\Hashtags\User\Repository $repo */
        $repo = Di::_()->get('Hashtags\User\Repository');

        $result = $repo->getAll(['user_guid' => Core\Session::getLoggedInUserGuid()]);

        return Factory::response([
            'status' => 'success',
            'tags' => $result
        ]);
    }

    public function post($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must provide a hashtag!'
            ]);
        }

        $hashtag = strtolower($pages[0]);
        if ($hashtag[0] === '#') {
            $hashtag = substr($hashtag, 1);
        }

        $user = Core\Session::getLoggedInUser();

        /** @var Core\Hashtags\User\Repository $repo */
        $repo = Di::_()->get('Hashtags\User\Repository');

        $entity = (new Core\Hashtags\HashtagEntity())
            ->setGuid($user->guid)
            ->setHashtag($hashtag);

        $result = $repo->add([$entity]);

        $user->setOptedInHashtags(1);
        $user->save();

        return Factory::response([
            'status' => 'success',
            'done' => $result
        ]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must provide a hashtag!'
            ]);
        }

        $hashtag = $pages[0];
        if ($hashtag[0] === '#') {
            $hashtag = substr($hashtag, 1);
        }

        $user = Core\Session::getLoggedInUser();

        /** @var Core\Hashtags\User\Repository $repo */
        $repo = Di::_()->get('Hashtags\User\Repository');

        $result = $repo->remove($user->guid, [$hashtag]);

        $user->setOptedInHashtags(-1);
        $user->save();

        return Factory::response([
            'status' => 'success',
            'done' => $result
        ]);
    }
}
