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

    public function __construct(\PDO $db, Category\Repository $categoryRepository = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
        $this->repository = $categoryRepository ?: Di::_()->get('Helpdesk\Category\Repository');
    }

    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'category_uuid' => '',
            'orderBy' => null // has to be a valid field
        ], $opts);

        $query = 'SELECT * FROM helpdesk_faq';
        $where = [];
        $values = [];

        if ($opts['category_uuid']) {
            $where[] = "category_uuid = ?";
            $values[] = $opts['category_uuid'];
        }

        $query .= ' WHERE' . implode(' AND ', $where);

        if ($opts['limit']) {
            $query .= " LIMIT = ?";
            $values[] = $opts['limit'];
        }

        if ($opts['offset']) {
            $query .= " OFFSET = ?";
            $values[] = $opts['offsaet'];
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
                ->setCategory($this->repository->getAll([
                    'uuid' => $row['category_uuid'],
                    'recursive' => true,
                ]))[0]
                ->setUserGuids(json_decode($row['user_guids']))
                ->setThumbsUpCount($row['thumbs_up_count'])
                ->setThumbsDownCount($row['thumbs_down_count']);

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
            $where[] = "question ILIKE '%?%' OR answer ILIKE '%?%'";
            $values[] = $opts['q'];
            $values[] = $opts['q'];
        }

        $query .= ' WHERE' . implode(' AND ', $where);

        if ($opts['limit']) {
            $query .= " LIMIT = ?";
            $values[] = $opts['limit'];
        }

        if ($opts['offset']) {
            $query .= " OFFSET = ?";
            $values[] = $opts['offsaet'];
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
                ->setCategory($this->repository->getAll([
                    'uuid' => $row['category_uuid'],
                    'recursive' => true
                ]))[0]
                ->setUserGuids(json_decode($row['user_guids']))
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
        $validFields = [
            'question',
            'answer',
            'thumbs_up_count',
            'thumbs_down_count',
            'user_guids'
        ];

        $query = "UPDATE helpdesk_faq SET ";

        $columns = [];
        $values = [];

        foreach ($validFields as $field) {
            if ($fields[$field] !== null) {
                $columns[] = "{$field} = ?";
                $values[] = $field == 'user_guids' ? json_encode($fields[$field]) : $fields[$field];
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