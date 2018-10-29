<?php

namespace Minds\Core\Helpdesk\Question;

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
        'answer',
        'thumbs_up_count',
        'thumbs_down_count',
    ];

    private static $orderDirections = [
        'ASC',
        'DESC',
    ];

    private static $questionFields = [
        'question',
        'answer',
        'thumbs_up_count',
        'thumbs_down_count',
        'thumbs_user_guids_count',
        'thumbs_user_guids_count',
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
                ->setCategoryUuid($row['category_uuid'])
                ->setUserGuids(json_decode($row['user_guids']))
                ->setThumbsUpCount($row['thumbs_up_count'])
                ->setThumbsDownCount($row['thumbs_down_count']);

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

    public function suggest(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'q' => ''
        ], $opts);

        $query = 'SELECT * FROM helpdesk_faq';
        $where = [];
        $values = [];

        if ($opts['q']) {
            $where[] = "question ILIKE ? OR answer ILIKE ?";
            $values[] = '%'.$opts['q'].'%';
            $values[] = '%'.$opts['q'].'%';
        }

        $query .= ' WHERE ' . implode(' AND ', $where);

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
                ->setUserGuids(json_decode($row['user_guids']))
                ->setUuid($row['uuid'])
                ->setThumbsUpCount($row['thumbs_up_count'])
                ->setThumbsDownCount($row['thumbs_down_count']);

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
                $values[] = in_array($field, [
                    'thumbs_up_user_guids',
                    'thumbs_down_user_guids'
                ]) ? json_encode($fields[$field]) : $fields[$field];
            }
        }

        $query .= implode(', ', $columns);

        $query .= " WHERE uuid = ?";
        $values[] = $question_uuid;

        $statement = $this->db->prepare($query);

        return $statement->execute($values);
    }

    public function registerThumbs(Question $entity, string $direction, int $value)
    {
        $query = "UPDATE helpdesk_faq
                      SET thumbs_{$direction}_count =
                        (SELECT SUM(thumbs_{$direction}_count) + ? FROM helpdesk_faq WHERE uuid = ?)
                      WHERE uuid = ?";
        $values = [$value, $entity->getUuid(), $entity->getUuid()];

        $statement = $this->db->prepare($query);

        return $statement->execute($values);
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