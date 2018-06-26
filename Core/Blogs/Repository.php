<?php

/**
 * Minds Blogs Repository
 *
 * @author emi
 */

namespace Minds\Core\Blogs;

use Minds\Common\Repository\Response;
use Minds\Core\Blogs\Legacy;
use Minds\Core\Data\Cassandra\Client;

class Repository
{
    /** @var Client */
    protected $cql;

    /** @var Legacy\Repository */
    protected $legacyRepository;

    /**
     * Repository constructor.
     * @param null $cql
     */
    public function __construct($legacyRepository = null)
    {
        $this->legacyRepository = $legacyRepository ?: new Legacy\Repository();
    }

    /**
     * Gets a list of blogs from the database
     * @param array $opts
     * @return Response
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        return $this->legacyRepository->getList($opts);
    }

    /**
     * Get a blog from the database
     * @param $guid
     * @return Blog
     */
    public function get($guid)
    {
        return $this->legacyRepository->get($guid);
    }

    /**
     * Adds a Blog to the database. Writes all attributes by default.
     * @param Blog $blog
     * @param array|null $attributes
     * @return int
     */
    public function add(Blog $blog, array $attributes = null)
    {
        $saved = $this->legacyRepository->add($blog, $attributes);

        if ($saved) {
            $blog->setEphemeral(false);
            $blog->markAllAsPristine();
        }

        return $saved;
    }

    /**
     * Updates a Blog in the database. It only writes dirty attributes.
     * @param Blog $blog
     * @return int
     */
    public function update(Blog $blog)
    {
        $saved = $this->legacyRepository->update($blog);

        if ($saved) {
            $blog->setEphemeral(false);
            $blog->markAllAsPristine();
        }

        return $saved;
    }

    /**
     * Deletes a Blog from the database
     * @param Blog $blog
     * @return bool
     */
    public function delete(Blog $blog)
    {
        $deleted = $this->legacyRepository->delete($blog);

        if ($deleted) {
            $blog->setEphemeral(true);
        }

        return $deleted;
    }
}
