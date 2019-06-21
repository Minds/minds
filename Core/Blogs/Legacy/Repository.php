<?php

/**
 * Minds Blogs Legacy Repository
 *
 * @author emi
 */

namespace Minds\Core\Blogs\Legacy;

use Cassandra;
use Minds\Common\Repository\Response;
use Minds\Core\Blogs\Blog;
use Minds\Core\Blogs\Legacy;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Feeds\Legacy\Repository as FeedsRepository;
use Minds\Core\Feeds\FeedItem;
use Minds\Core\Security\ACL;

class Repository
{
    /** @var Client */
    protected $cql;

    /** @var Legacy\Entity */
    protected $entity;

    /** @var FeedsRepository */
    protected $feedsRepo;

    /** @var ACL */
    protected $acl;

    /**
     * Repository constructor.
     * @param null $cql
     * @param null $legacyEntity
     */
    public function __construct($cql = null, $legacyEntity = null, $feedsRepo = null, $acl = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->entity = $legacyEntity ?: new Entity();
        $this->feedsRepo = $feedsRepo ?: new FeedsRepository();
        $this->acl = $acl ?: ACL::_();
    }

    /**
     * Gets a list of blogs from the database
     * @param array $opts
     * @return Response
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => null,
            'offset' => '',
            'type' => 'object',
            'subtype' => 'blog',
            'user' => null,
            'network' => null,
            'container' => null,
            'all' => false,
            'guids' => null,
            'lt' => null,
            'gt' => null,
            'reversed' => true,
        ], $opts);

        if (!$opts['guids']) {
            
            $response = $this->feedsRepo->getList($opts);
            
            $pagingToken = base64_encode($response->getPagingToken());

            $guids = [];
            foreach ($response as $item) {
                $guids[] = (string) $item->getGuid();
            }

        } else {
            $guids = $opts['guids'];
            $pagingToken = '';
        }

        $requests = [];

        foreach ($guids as $guid) {
            $cql = "SELECT * FROM entities WHERE key = ?";
            $values = [ $guid ];

            $prepared = new Custom();
            $prepared->query($cql, $values);

            try {
                $requests[$guid] = $this->cql->request($prepared, true);
            } catch (\Exception $e) {
                error_log('Blogs/Legacy/Repository::getList/entities ' . get_class($e) . ' ' . $e->getMessage());
                return (new Response())->setException($e);
            }
        }

        $blogs = new Response();
        $blogs->setPagingToken($pagingToken);

        foreach ($requests as $key => $future) {
            if ($rows = $future->get()) {
                $data = [];

                foreach ($rows as $row) {
                    if (!isset($data['guid'])) {
                        $data['guid'] = $row['key'];
                    }

                    $data[$row['column1']] = $row['value'];
                }

                if ($data) {
                    $blog = $this->entity->build($data);
                    $blog->setEphemeral(false);

                    if ($this->acl->read($blog)) {
                        $blogs[] = $blog;
                    }
                }
            }
        }

        return $blogs;
    }

    /**
     * Get a blog from the database
     * @param $guid
     * @return Blog
     */
    public function get($guid)
    {
        $cql = "SELECT * FROM entities WHERE key = ?";
        $values = [ (string) $guid ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $rows = $this->cql->request($prepared);

            if (!$rows) {
                return null;
            }

            $data = [];

            foreach ($rows as $row) {
                if (!isset($data['guid'])) {
                    $data['guid'] = $row['key'];
                }

                $data[$row['column1']] = $row['value'];
            }

            if (!$data) {
                return null;
            }
        } catch (\Exception $e) {
            error_log('Blogs/Legacy/Repository::get ' . get_class($e) . ' ' . $e->getMessage());
            return null;
        }

        $blog = $this->entity->build($data);
        $blog->setEphemeral(false);

        return $blog;
    }

    /**
     * Adds a Blog to the database. Writes all attributes by default.
     * @param Blog $blog
     * @param array|null $attributes
     * @return int
     */
    public function add(Blog $blog, array $attributes = null)
    {
        $guid = $blog->getGuid();
        if ($attributes === null) {
            $attributes = array_keys(Legacy\Entity::$attributeMap);
        }

        $fields = [];

        foreach ($attributes as $attribute) {
            if (!isset(Legacy\Entity::$attributeMap[$attribute])) {
                continue;
            }

            $getter = 'get' . ucfirst($attribute);
            $fields[Legacy\Entity::$attributeMap[$attribute]] = $blog->$getter();
        }

        foreach (Legacy\Entity::$jsonEncodedFields as $jsonEncodedField) {
            if (isset($fields[$jsonEncodedField]) && !is_string($fields[$jsonEncodedField])) {
                $fields[$jsonEncodedField] = json_encode($fields[$jsonEncodedField]);
            }
        }

        foreach (Legacy\Entity::$boolFields as $boolField) {
            if (isset($fields[$boolField])) {
                $fields[$boolField] = $fields[$boolField] ? '1' : '0';
            }
        }

        if (isset($fields['published'])) {
            $fields['published'] = $fields['published'] ? '1' : '0';
        }

        if (!$fields) {
            return $guid;
        }

        $requests = [];

        foreach ($fields as $column1 => $value) {
            $requests[] = [
                'string' => "INSERT into entities (key, column1, value) VALUES (?, ?, ?)",
                'values' => [ (string) $guid, $column1, (string) $value ],
            ];
        }

        try {
            $this->cql->batchRequest($requests, Cassandra::BATCH_UNLOGGED, false);
        } catch (\Exception $e) {
            error_log('Blogs/Legacy/Repository::add ' . get_class($e) . ' ' . $e->getMessage());
            return false;
        }

        return $guid;
    }

    /**
     * Updates a Blog in the database. It only writes dirty attributes.
     * @param Blog $blog
     * @return int
     */
    public function update(Blog $blog)
    {
        return $this->add($blog, $blog->getDirtyAttributes());
    }

    /**
     * Deletes a Blog from the database
     * @param Blog $blog
     * @return bool
     */
    public function delete(Blog $blog)
    {
        $cql = "DELETE FROM entities WHERE key = ?";
        $values = [
            (string) $blog->getGuid()
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $this->cql->request($prepared);
        } catch (\Exception $e) {
            error_log('Blogs/Legacy/Repository::delete ' . get_class($e) . ' ' . $e->getMessage());
            return false;
        }

        return true;
    }
}
