<?php

namespace Minds\Core\Helpdesk\Question;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;
use Minds\Core\Helpdesk\Category;

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
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'category_uuid' => null,
        ], $opts);

        $query = "SELECT * FROM helpdesk_faq ";
        $where = [];
        $values = [];

        if ($opts['category_uuid']) {
            $where[] = "category_uuid = ?";
            $values[] = $opts['category_uuid'];
        }

        if (count($where) > 0) {
            $query .= 'WHERE ' . implode(' AND ', $where);
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


        $response = new Response();

        foreach ($data as $row) {
            $question = new Question();
            $question->setUuid($row['uuid'])
                ->setQuestion($row['question'])
                ->setAnswer($row['answer'])
                ->setCategoryUuid($row['category_uuid']);

            $response[] = $question;
        }
        $response->setPagingToken((int) $opts['offset'] + (int) $opts['limit']);

        return $response;
    }

    /**
     * Return a single question
     * @param string $uuid
     * @return Question
     */
    public function get($uuid, $user_guid)
    {
        $query = "SELECT q.*, v.direction AS voted 
            FROM helpdesk_faq q 
            LEFT JOIN helpdesk_votes v 
                ON v.question_uuid = q.uuid 
                AND v.user_guid = ?
            WHERE q.uuid = ?";
        
        $values = [
            $user_guid,
            $uuid,
        ];

        $statement = $this->db->prepare($query);

        $statement->execute($values);

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        $question = new Question();
        $question->setUuid($row['uuid'])
            ->setQuestion($row['question'])
            ->setAnswer($row['answer'])
            ->setCategoryUuid($row['category_uuid'])
            ->setThumbUp($row['voted'] === 'up')
            ->setThumbDown($row['voted'] === 'down');

        return $question;
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

        $query = "SELECT q.question, q.answer, q.uuid, count(user_guid) AS votes
            FROM helpdesk_votes v
            JOIN helpdesk_faq q
              ON q.uuid=v.question_uuid
            WHERE v.direction='up'
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

                $question->setQuestion($row['question'])
                    ->setAnswer($row['answer'])
                    ->setCategoryUuid($row['category_uuid'])
                    ->setUuid($row['uuid']);

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

        $query = "SELECT q.*, c.branch
            FROM helpdesk_faq q
            JOIN helpdesk_categories c 
                ON c.uuid = q.category_uuid";
        $where = [];
        $values = [];

        if ($opts['q']) {
            $where[] = "q.question ILIKE ? OR q.answer ILIKE ?";
            $values[] = '%' . $opts['q'] . '%';
            $values[] = '%' . $opts['q'] . '%';
        }

        $query .= ' WHERE ' . implode(' AND ', $where);
        $query .= ' ORDER BY c.branch';

        if ($opts['limit']) {
            $query .= " LIMIT ?";
            $values[] = (int) $opts['limit'];
        }

        if ($opts['offset']) {
            $query .= " OFFSET ?";
            $values[] = (int) $opts['offset'];
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
                ->setUuid($row['uuid']);

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

        $query = "INSERT INTO helpdesk_faq (question, answer, category_uuid) VALUES (?,?,?) RETURNING uuid";

        $values = [
            $entity->getQuestion(),
            $entity->getAnswer(),
            $entity->getCategoryUuid(),
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
