<?php

namespace Minds\Core\Helpdesk\Category;

use Cassandra\Uuid;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Util\UUIDGenerator;

class Repository
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'uuid' => '',
            'recursive' => false,
        ], $opts);

        $query = "SELECT * FROM helpdesk_categories ";

        $where = [];
        $values = [];

        if ($opts['uuid']) {
            $where[] = 'uuid = ?';
            $values[] = new Uuid($opts['uuid']);
        }

        if (count($where) > 0) {
            $query .= ' WHERE ' . implode('AND', $where);
        }

        $prepared = (new Custom())
            ->query($query, $values);

        $response = new Response();

        try {
            $data = $this->client->request($prepared);

            foreach ($data as $row) {
                $category = new Category();
                $category->setUuid($row['uuid']->uuid())
                    ->setTitle($row['title'])
                    ->setParentUuid($row['parent'])
                    ->setBranch($row['branch'])
                    ->setPosition($row['position'] ?? 10);

                if ($opts['recursive']) {
                    if ($category->getParentUuid()) {
                        $branch = $this->getBranch($category->getParentUuid());
                        $category->setParent($branch);
                    }
                }

                $response[] = $category;
            }

            $response->setPagingToken((int) $opts['offset'] + (int) $opts['limit']);
        } catch (\Exception $e) {
            error_log($e);
        }

        return $response;
    }

    /**
     * Get one category by uuid
     *
     * @param string $uuid
     * @return Category
     */
    public function get($uuid)
    {
        $query = "SELECT * FROM helpdesk_categories WHERE uuid = ?";

        $prepared = (new Custom())
            ->query($query, [new Uuid($uuid)]);

        try {
            $rows = $this->client->request($prepared);

            $category = null;

            foreach ($rows as $row) {
                $category = new Category();
                $category->setUuid($row['uuid']->uuid())
                    ->setTitle($row['title'])
                    ->setParentUuid($row['parent'])
                    ->setBranch($row['branch']);
            }

            return $category;
        } catch (\Exception $e) {
            error_log($e);
            return null;
        }
    }

    /**
     * Get the categories branch given an uuid
     *
     * @param string $uuid
     * @return Category
     */
    public function getBranch($uuid)
    {
        $leaf = $this->get($uuid);

        if (!$leaf) return null;

        $branch = explode(':', $leaf->getBranch());
        array_pop($branch);

        $child = $leaf;
        foreach (array_reverse($branch) as $parent_uuid) {
            $parent = $this->get($parent_uuid);
            $child->setParent($parent);
            $child = $parent;
        }

        return $leaf;
    }

    /**
     * Add a new category
     *
     * @param Category $category
     * @return string|false
     */
    public function add(Category $category)
    {
        $query = "INSERT INTO helpdesk_categories(uuid, title, parent, branch) VALUES (?,?,?,?)";
        
        $uuid = $category->getUuid() ?: UUIDGenerator::generate();

        $values = [
            new Uuid($uuid),
            $category->getTitle(),
            $category->getParentUuid() ? new Uuid($category->getParentUuid()) : null,
            $category->getParentUuid() ? $this->generateBranch($uuid, $category->getParentUuid()) : $uuid
        ];

        $prepared = (new Custom())
            ->query($query, $values);

        try {
            if (!$this->client->request($prepared)) {
                return false;
            }
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }

        return $uuid;
    }

    /**
     * Delete a category
     *
     * @param string $category_uuid
     * @return bool
     */
    public function delete(string $category_uuid)
    {
        $query = "DELETE FROM helpdesk_categories WHERE uuid = ?";

        $values = [new Uuid($category_uuid)];

        try {
            $prepared = (new Custom())
                ->query($query, $values);

            return !!$this->client->request($prepared);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * Generate the branch field for a category
     *
     * @param string $uuid
     * @param string $parent_uuid
     * @return string
     */
    protected function generateBranch($uuid, $parent_uuid)
    {
        $query = 'SELECT branch FROM helpdesk_categories WHERE uuid = ?';
        $prepared = (new Custom())
            ->query($query, [new Uuid($parent_uuid)]);

        try {
            $response = $this->client->request($prepared);
            return $response->current()['branch'] . ':' . $uuid;

        } catch (\Exception $e) {
            error_log($e);
        }
        return $uuid;
    }
}
