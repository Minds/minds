<?php

/**
 * Minds Trending Blogs
 *
 * @author emi
 */

namespace Minds\Core\Blogs;

use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;

class Trending
{
    /** @var Repository */
    protected $repository;

    /** @var \Minds\Core\Trending\Repository */
    protected $trendingRepository;

    /**
     * Trending constructor.
     * @param null $repository
     * @param null $trendingRepository
     */
    public function __construct(
        $repository = null,
        $trendingRepository = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->trendingRepository = $trendingRepository ?: Di::_()->get('Trending\Repository');
    }

    /**
     * @param array $opts
     * @return Response
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge($opts, [
            'type' => 'blogs'
        ]);

        $result = $this->trendingRepository->getList($opts);

        if (!$result) {
            return new Response();
        }

        $blogs = $this->repository->getList([
            'guids' => $result['guids'],
        ]);
        $blogs->setPagingToken(base64_encode($result['token']));
        return $blogs;
    }
}
