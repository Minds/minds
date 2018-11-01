<?php

namespace Minds\Core\Helpdesk\Question;

use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Category;
use Minds\Core\Helpdesk\Entities\Question;
use Minds\Core;

class Repository
{
    /** @var \PDO */
    protected $db;
    /** @var Category\Repository */
    protected $repository;

    private static $orderByFields = [
        'question',
        'answer'
    ];

    private static $orderDirections = [
        'ASC',
        'DESC',
    ];

    private static $questionFields = [
        'question',
        'answer',
    ];

    public function __construct(\PDO $db = null, Category\Repository $categoryRepository = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
        $this->repository = $categoryRepository ?: Di::_()->get('Helpdesk\Category\Repository');
    }

    /**
     * @param array $opts
     * @return Question[]
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'category_uuid' => null,
            'question_uuid' => null,
            'orderBy' => null, // has to be a valid field
            'orderDirection' => 'DESC',
            'hydrateCategory' => false,
        ], $opts);

        $query = 'SELECT * FROM helpdesk_faq ';
        $where = [];
        $values = [];

        if ($opts['question_uuid']) {
            $where[] = "uuid = ?";
            $values[] = $opts['question_uuid'];
        }

        if ($opts['category_uuid']) {
            $where[] = "category_uuid = ?";
            $values[] = $opts['category_uuid'];
        }

        if (count($where) > 0) {
            $query .= 'WHERE ' . implode(' AND ', $where);
        }


        if ($opts['orderBy'] && in_array($opts['orderBy'], self::$orderByFields)) {
            $query .= " ORDER BY {$opts['orderBy']} ";

            if ($opts['orderDirection'] && in_array(strtoupper($opts['orderDirection']), self::$orderDirections)) {
                $query .= $opts['orderDirection'];
            }
        }

        if ($opts['limit']) {
            $query .= ' LIMIT ?';
            $values[] = $opts['limit'];
        }

        if ($opts['offset']) {
            $query .= ' OFFSET ?';
            $values[] = $opts['offset'];
        }

        $statement = $this->db->prepare($query);

        $statement->execute($values);

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];

        foreach ($data as $row) {
            $question = new Question();
            $question->setUuid($row['uuid'])
                ->setQuestion($row['question'])
                ->setAnswer($row['answer'])
                ->setCategoryUuid($row['category_uuid']);

            if ($opts['hydrateCategory']) {
                $question->setCategory($this->repository->getAll([
                    'uuid' => $row['category_uuid'],
                    'recursive' => false,
                ])[0]);
            }

            $result[] = $question;
        }

        return $result;
    }

    public function top(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 8,
        ], $opts);
        $limit = intval($opts['limit']);


        $query = "SELECT question_uuid, count(user_guid) as votes FROM helpdesk_votes WHERE type = true GROUP BY question_uuid ORDER BY votes desc LIMIT $limit";

        $statement = $this->db->prepare($query);
        $statement->execute();
        // TODO: CACHE!
        $questions_uuids = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];

        if (count($questions_uuids)) {
            $column = array_column($questions_uuids, 'question_uuid');

            $q = implode(',',array_fill(0, count($column),'?'));

            $query = "SELECT q.*, c.branch, v.type as voted
                FROM helpdesk_faq q JOIN helpdesk_categories c on c.uuid = q.category_uuid
                LEFT JOIN helpdesk_votes v on v.question_uuid = q.uuid and v.user_guid = ?
                WHERE q.uuid in ($q)";

            $values[] = Core\Session::getLoggedinUser()->guid;
            array_push($values, ...$column);

            $statement = $this->db->prepare($query);
            $statement->execute($values);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $row) {
                $question = new Question();
                $question->setQuestion($row['question'])
                    ->setAnswer($row['answer'])
                    ->setCategoryUuid($row['category_uuid'])
                    ->setCategory($this->repository->getBranch($row['category_uuid']))
                    ->setUuid($row['uuid'])
                    ->setThumbUp($row['voted'] === true)
                    ->setThumbDown($row['voted'] === false);

                $result[] = $question;
            }

        }

        return $result;
    }

    /**
     * Suggested questions
     *
     * @param array $opts
     * @return void
     */
    public function suggest(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'q' => ''
        ], $opts);

        $query = "SELECT q.*, c.branch, v.type as voted
            FROM helpdesk_faq q JOIN helpdesk_categories c on c.uuid = q.category_uuid
            LEFT JOIN helpdesk_votes v on v.question_uuid = q.uuid and v.user_guid = ?";
        $where = [];
        $values = [];

        $values[] = Core\Session::getLoggedinUser()->guid;

        if ($opts['q']) {
            $where[] = "q.question ILIKE ? OR q.answer ILIKE ?";
            $values[] = '%'.$opts['q'].'%';
            $values[] = '%'.$opts['q'].'%';
        }

        $query .= ' WHERE ' . implode(' AND ', $where);
        $query .= ' ORDER BY c.branch';

        if ($opts['limit']) {
            $query .= " LIMIT ". intval($opts['limit']);
        }

        if ($opts['offset']) {
            $query .= " OFFSET = ?";
            $values[] = $opts['offset'];
        }

        $statement = $this->db->prepare($query);

        $statement->execute($values);

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];

        foreach ($data as $row) {
            $question = new Question();
            $question->setQuestion($row['question'])
                ->setAnswer($row['answer'])
                ->setCategoryUuid($row['category_uuid'])
                ->setCategory($this->repository->getBranch($row['category_uuid']))
                ->setUuid($row['uuid'])
                ->setThumbUp($row['voted'] === true)
                ->setThumbDown($row['voted'] === false);

            $result[] = $question;
        }

        return $result;
    }

    public function add(Question $entity)
    {
        $query = "UPSERT INTO helpdesk_faq (uuid, question, answer, category_id) VALUES(?,?,?,?)";

        $values = [
            $entity->getUuid(),
            $entity->getQuestion(),
            $entity->getAnswer(),
            $entity->getCategoryUuid()
        ];

        try {
            $statement = $this->db->prepare($query);

            return $statement->execute($values);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    public function update(string $question_uuid, array $fields)
    {

        $query = "UPDATE helpdesk_faq SET ";

        $columns = [];
        $values = [];

        foreach (self::$questionFields as $field) {
            if ($fields[$field] !== null) {
                $columns[] = "{$field} = ?";
                $values[] = $fields[$field];
            }
        }

        $query .= implode(', ', $columns);

        $query .= " WHERE uuid = ?";
        $values[] = $question_uuid;

        $statement = $this->db->prepare($query);

        return $statement->execute($values);
    }

    public function vote($uuid, $direction)
    {
        // because driver fails to prepare a value of false
        $d = $direction === 'up' ? 'true' : 'false';

        $query = "INSERT INTO helpdesk_votes (question_uuid, user_guid, type) VALUES(?,?,$d) ON CONFLICT (question_uuid, user_guid) DO UPDATE SET type = excluded.type;";

        $values = [
            $uuid,
            Core\Session::getLoggedinUser()->guid
        ];

        try {
            $statement = $this->db->prepare($query);

            return $statement->execute($values);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    public function unvote($uuid)
    {
        $query = "DELETE FROM helpdesk_votes WHERE question_uuid = ? AND user_guid = ?";

        $values = [$uuid, Core\Session::getLoggedinUser()->guid];

        try {
            $statement = $this->db->prepare($query);
            return $statement->execute($values);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    public function registerThumbs(Question $entity, string $direction, int $value)
    {
        // TODO: IMPLEMENT NEW

        // $query = "UPDATE helpdesk_faq
        //               SET thumbs_{$direction}_count =
        //                 (SELECT SUM(thumbs_{$direction}_count) + ? FROM helpdesk_faq WHERE uuid = ?)
        //               WHERE uuid = ?";
        // $values = [$value, $entity->getUuid(), $entity->getUuid()];

        // $statement = $this->db->prepare($query);

        // return $statement->execute($values);
    }

    public function delete(string $question_uuid)
    {
        $query = "DELETE FROM helpdesk_faq WHERE uuid = ?";

        $values = [$question_uuid];

        try {
            $statement = $this->db->prepare($query);

            return $statement->execute($values);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }
}