<?php

namespace Minds\Core\Helpdesk\Question;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Category;
use Minds\Core\Helpdesk\Entities\Question;

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
     * Get questions
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
            'user_guid' => null, // for thumbs
            'orderBy' => null, // has to be a valid field
            'orderDirection' => 'DESC',
            'hydrateCategory' => false,
        ], $opts);

        $query = 'SELECT * FROM helpdesk_faq ';
        $where = [];
        $values = [];

        if ($opts['user_guid']) {
            $query = 'SELECT q.*, v.type AS voted FROM helpdesk_faq q 
                      LEFT JOIN helpdesk_votes v ON v.question_uuid = q.uuid AND v.user_guid = ? ';
            $values[] = $opts['user_guid'];
        }

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
                ->setCategoryUuid($row['category_uuid'])
                ->setThumbUp($row['voted'] === true)
                ->setThumbDown($row['voted'] === false);

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

    /**
     * Return the TOP questions by up votes
     *
     * @param array $opts
     * @return array
     */
    public function top(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 8,
        ], $opts);
        $limit = intval($opts['limit']);

        $query = "SELECT q.question,q.answer,q.uuid,count(user_guid)AS votes
            FROM helpdesk_votes v
            JOIN helpdesk_faq q ON q.uuid=v.question_uuid
            WHERE TYPE=TRUE
            GROUP BY uuid,question,answer
            ORDER BY votes DESC
            LIMIT $limit";

        $statement = $this->db->prepare($query);
        $statement->execute();
        // TODO: CACHE THIS RESULT!
        $topQuestions = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];

        if (count($topQuestions)) {

            $userGuid = Core\Session::getLoggedinUser()->guid;

            foreach ($topQuestions as $row) {
                $question = new Question();
                $voted = $this->getVote($row['uuid'], $userGuid);

                $question->setQuestion($row['question'])
                    ->setAnswer($row['answer'])
                    ->setCategoryUuid($row['category_uuid'])
                    ->setUuid($row['uuid'])
                    ->setThumbUp($voted === true)
                    ->setThumbDown($voted === false);

                $result[] = $question;
            }
        }

        return $result;
    }

    /**
     * Suggested questions
     *
     * @param array $opts
     * @return array
     */
    public function suggest(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'q' => ''
        ], $opts);

        $query = "SELECT q.*, c.branch, v.type AS voted
            FROM helpdesk_faq q JOIN helpdesk_categories c ON c.uuid = q.category_uuid
            LEFT JOIN helpdesk_votes v ON v.question_uuid = q.uuid AND v.user_guid = ?";
        $where = [];
        $values = [];

        $values[] = Core\Session::getLoggedinUser()->guid;

        if ($opts['q']) {
            $where[] = "q.question ILIKE ? OR q.answer ILIKE ?";
            $values[] = '%' . $opts['q'] . '%';
            $values[] = '%' . $opts['q'] . '%';
        }

        $query .= ' WHERE ' . implode(' AND ', $where);
        $query .= ' ORDER BY c.branch';

        if ($opts['limit']) {
            $query .= " LIMIT " . intval($opts['limit']);
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

    /**
     * Add a question
     *
     * @param Question $entity
     * @return string|false
     */
    public function add(Question $entity)
    {
        $uuid = $entity->getUuid() ?: Core\Util\UUIDGenerator::generate();

        $query = "UPSERT INTO helpdesk_faq (uuid, question, answer, category_uuid) VALUES(?,?,?,?) RETURNING uuid";

        $values = [
            $uuid,
            $entity->getQuestion(),
            $entity->getAnswer(),
            $entity->getCategoryUuid()
        ];

        try {
            $statement = $this->db->prepare($query);

            $statement->execute($values);

            return $statement->fetch(\PDO::FETCH_ASSOC)['uuid'];
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * Update a question
     *
     * @param string $question_uuid
     * @param array $fields
     * @return void
     */
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

    /**
     * Vote a question for the current user
     *
     * @param string $uuid
     * @param string $direction
     * @return void
     */
    public function vote($uuid, $direction)
    {
        // because driver fails to prepare a value of false
        $d = $direction === 'up' ? 'true' : 'false';

        $query = "INSERT INTO helpdesk_votes (question_uuid, user_guid, type)
                    VALUES(?,?,$d)
                  ON CONFLICT (question_uuid, user_guid) DO UPDATE SET type = excluded.type;";

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

    /**
     * Delete a vote for the given question for the current user
     *
     * @param string $uuid
     * @return void
     */
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

    /**
     * Get vote for a given question/user
     *
     * @param string $uuid
     * @param string $userGuid
     * @return boolean|null true thumbUp, false thumbDown, null not voted
     */
    public function getVote($uuid, $userGuid)
    {
        $statement = $this->db->prepare("SELECT type FROM helpdesk_votes WHERE question_uuid = ? AND user_guid = ?");

        $statement->execute([$uuid, $userGuid]);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return ($result) ? $result['type'] : null;
    }

    /**
     * Delete a question
     *
     * @param string $question_uuid
     * @return void
     */
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