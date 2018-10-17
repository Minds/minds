<?php

namespace Minds\Core\Helpdesk\Category;

use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Entities\Category;
use Minds\Core\Util\UUIDGenerator;

class Repository
{
    /** @var \PDO */
    protected $db;

    public function __construct(\PDO $db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
    }

    /**
     * @param array $opts
     * @return Category[]
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'uuid' => '',
            'recursive' => false,
        ], $opts);

        // gets all categories, with parents

        $query = null;
        if ($opts['recursive']) {
            $query = "SELECT cats1.uuid parent_uuid, cats1.title parent_title, cats1.parent parent_parent,
                    cats2.uuid child_uuid, cats2.title child_title, cats2.parent child_parent
                  FROM helpdesk_categories cats1
	              LEFT JOIN helpdesk_categories as cats2 ON cats2.parent = cats1.uuid";
        } else {
            $query = "SELECT * FROM helpdesk_categories";
        }

        $where = [];
        $values = [];

        if ($opts['uuid']) {
            $where[] = 'uuid = ?';
            $values[] = $opts['uuid'];
        }

        if (count($where) > 0) {
            $query .= ' WHERE ' . implode('AND', $where);
        }

        $statement = $this->db->prepare($query);

        $statement->execute($values);

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];

        foreach ($data as $row) {
            $category = new Category();
            $category->setUuid($row['uuid'])
                ->setTitle($row['title'])
                ->setParentUuid($row['parent'])
                ->setBranch($row['branch']);

            $result[] = $category;
        }

        if ($opts['recursive']) {
            $roots = [];
            for ($i = 0; $i < count($result); $i++) {
                $category1 = $result[$i];

                if (!$category1->getParentUuid()) {
                    $roots[] = $category1;
                    continue;
                }

                // get the parent.
                $filtered = array_filter($result, function ($cat) use ($category1) {
                    return $cat->getUuid() === $category1->getParentUuid();
                });

                // the length will be either 0 or 1
                if (count($filtered) > 0) {
                    $category1->setParent($filtered[0]);
                }
            }

            return $roots;
        }

        return $result;
    }

    public function add(Category $category)
    {
        $query = "INSERT INTO helpdesk_categories(uuid, title, parent, branch) VALUES (?,?,?,?)";
        $uuid = UUIDGenerator::generate();

        // we need to do this as cockroachdb doesn't yet support triggers
        $parent = $category->getParent();
        if (!$parent && $category->getParentUuid() !== null) {
            $parent = $this->getAll(['uuid' => $category->getParentUuid()])[0];
        }
        $values = [
            $uuid,
            $category->getTitle(),
            $category->getParentUuid(),
            $parent ? $parent->getBranch() . ':' . $uuid : $uuid
        ];

        $statement = $this->db->prepare($query);

        if (!$statement->execute($values)) {
            return false;
        }

        return $uuid;
    }
}