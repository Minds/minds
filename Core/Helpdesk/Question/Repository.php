<?php

namespace Minds\Core\Helpdesk\Question;

use Cassandra\Set;
use Cassandra\Type;
use Cassandra\Uuid;
use Cassandra\Varint;
use Minds\Common\Repository\Response;
use Minds\Core;
use Minds\Core\Data\Cassandra;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Category;

class Repository
{
    /** @var Core\Data\Cassandra\Client */
    protected $cassandraClient;

    /** @var Core\Data\ElasticSearch\Client */
    protected $esClient;

    /** @var Category\Repository */
    protected $repository;

    public function __construct(
        Cassandra\Client $cassandraClient = null,
        ElasticSearch\Client $esClient = null,
        Category\Repository $categoryRepository = null
    )
    {
        $this->cassandraClient = $cassandraClient ?: Di::_()->get('Database\Cassandra\Cql');
        $this->esClient = $esClient ?: Di::_()->get('Database\ElasticSearch');
        $this->repository = $categoryRepository ?: Di::_()->get('Helpdesk\Category\Repository');
    }

    /**
     * Get questions
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => '',
            'category_uuid' => null,
        ], $opts);

        $query = "SELECT * FROM helpdesk_faq_by_category_uuid ";
        $where = [];
        $values = [];

        if ($opts['category_uuid']) {
            $where[] = "category_uuid = ?";
            $values[] = new Uuid($opts['category_uuid']);
        }

        if (count($where) > 0) {
            $query .= 'WHERE ' . implode(' AND ', $where);
        }

        $prepared = (new Custom())
            ->query($query, $values);

        $prepared->setOpts([
            'page_size' => (int) $opts['limit'],
            'paging_state_token' => base64_decode($opts['offset']),
        ]);

        $response = new Response([]);

        try {
            $data = $this->cassandraClient->request($prepared);

            foreach ($data as $row) {
                $question = new Question();

                $question->setUuid($row['uuid']->uuid())
                    ->setQuestion($row['question'])
                    ->setAnswer($row['answer'])
                    ->setCategoryUuid($row['category_uuid'] ? $row['category_uuid']->uuid() : null)
                    ->setScore($row['score']);

                $response[] = $question;
            }
            $response->setPagingToken($data->pagingStateToken());
        } catch (\Exception $e) {
            error_log($e);
        }

        return $response;
    }

    /**
     * Return a single question
     * @param $uuid
     * @return Question
     */
    public function get($uuid)
    {
        if (!isset($uuid)) {
            throw new \Exception('uuid must be provided');
        }

        $query = "SELECT * FROM helpdesk_faq WHERE uuid = ?";

        $prepared = (new Custom())
            ->query($query, [new Uuid($uuid)]);

        $question = null;
        try {
            $result = $this->cassandraClient->request($prepared);
            if ($result->count() > 0) {
                $row = $result->current();

                $question = (new Question())
                    ->setUuid($row['uuid']->uuid())
                    ->setQuestion($row['question'])
                    ->setAnswer($row['answer'])
                    ->setCategoryUuid($row['category_uuid'] ? $row['category_uuid']->uuid() : null)
                    ->setThumbsUp($this->getThumbsFromSet($row['votes_up']))
                    ->setThumbsDown($this->getThumbsFromSet($row['votes_down']));
            }
        } catch (\Exception $e) {
            error_log($e);
        }

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

        try {
            $results = $this->esClient->getClient()->search([
                'index' => 'minds-helpdesk',
                'size' => $opts['limit'],
                'sort' => ['score:desc'],
                'body' => [
                    'query' => [
                        'match_all' => (object) [] // need to cast empty array to object to allow this to work
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            return [];
        }

        if (!isset($results['hits']) || !isset($results['hits']['hits'])) {
            return [];
        }

        $uuids = array_map(function ($document) {
            return $document['_source']['uuid'];
        }, $results['hits']['hits']);

        $questions = [];

        foreach ($uuids as $uuid) {
            $questions[] = $this->get($uuid);
        }

        return $questions;
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

        $query = new Core\Data\ElasticSearch\Prepared\Suggest();

        $query->query('minds-helpdesk', $opts['q'], ['size' => $opts['limit']]);

        try {
            $results = $this->esClient->request($query);

            if (!isset($results['suggest']['autocomplete'][0]['options'])) {
                return [];
            }

            $uuids = array_map(function ($document) {
                return $document['_source']['uuid'];
            }, $results['suggest']['autocomplete'][0]['options']);

            $entities = [];
            foreach ($uuids as $uuid) {
                $entities[] = $this->get($uuid);
            }

            return $entities;
        } catch (\Exception $e) {
            error_log($e);
        }
    }

    /**
     * Add a question
     *
     * @param Question $entity
     * @return string|false
     */
    public function add(Question $entity)
    {
        if (!$entity->getCategoryUuid()) {
            throw new \Exception('categoryUuid must be provided');
        }
        
        $uuid = $entity->getUuid() ?: Core\Util\UUIDGenerator::generate();
        $query = "INSERT INTO helpdesk_faq (uuid, question, answer, category_uuid) VALUES (?,?,?,?)";

        $values = [
            new Uuid($uuid),
            $entity->getQuestion(),
            $entity->getAnswer(),
            new Uuid($entity->getCategoryUuid()),
        ];

        $prepared = (new Custom())
            ->query($query, $values);

        try {
            $this->cassandraClient->request($prepared);

            $prepared = new Core\Data\ElasticSearch\Prepared\Index();
            $prepared->query([
                'index' => 'minds-helpdesk',
                'id' => $uuid,
                'type' => 'question',
                'body' => [
                    'uuid' => $uuid,
                    'score' => $entity->getScore() ?: 1,
                    'category_uuid' => $entity->getCategoryUuid(),
                    'question' => $entity->getQuestion(),
                    'answer' => $entity->getAnswer(),
                    'suggest' => [
                        'input' => array_merge([
                            $entity->getQuestion(),
                            $entity->getAnswer()
                        ], $this->permutateString($entity->getQuestion()))
                    ]
                ]
            ]);

            $this->esClient->request($prepared);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
        return $uuid;
    }

    /**
     * Update a question
     *
     * @param Question $question
     * @return bool
     */
    public function update(Question $question)
    {

        $query = "UPDATE helpdesk_faq SET ";

        $query .= " question = ?, answer = ?, category_uuid = ?, score = ?, votes_up = ?, votes_down = ? WHERE uuid = ?";

        $thumbsUp = new Set(Type::varint());
        foreach ($question->getThumbsUp() as $userGuid) {
            $thumbsUp->add(new Varint($userGuid));
        }

        $thumbsDown = new Set(Type::varint());
        foreach ($question->getThumbsDown() as $userGuid) {
            $thumbsDown->add(new Varint($userGuid));
        }

        $values = [
            $question->getQuestion(),
            $question->getAnswer(),
            $question->getCategoryUuid() ? new Uuid($question->getCategoryUuid()) : null,
            $question->getScore() ?: 1,
            $thumbsUp,
            $thumbsDown,
            new Uuid($question->getUuid()),
        ];

        $prepared = (new Custom())
            ->query($query, $values);

        try {
            $result = $this->cassandraClient->request($prepared);

            $prepared = new Core\Data\ElasticSearch\Prepared\Index();
            $prepared->query([
                'index' => 'minds-helpdesk',
                'id' => $question->getUuid(),
                'type' => 'question',
                'body' => [
                    'uuid' => $question->getUuid(),
                    'score' => $question->getScore() ?: 1,
                    'category_uuid' => $question->getCategoryUuid(),
                    'question' => $question->getQuestion(),
                    'answer' => $question->getAnswer(),
                    'suggest' => ['input' => [$question->getQuestion(), $question->getAnswer()]]
                ]
            ]);

            $this->esClient->request($prepared);

            return !!$result;
        } catch (\Exception $e) {
            error_log($e);
        }

        return false;
    }

    /**
     * Delete a question
     *
     * @param string $question_uuid
     * @return bool
     */
    public function delete(string $question_uuid)
    {
        $query = "DELETE FROM helpdesk_faq WHERE uuid = ?";

        $values = [new Uuid($question_uuid)];

        $prepared = (new Custom())
            ->query($query, $values);

        try {
            $result = $this->cassandraClient->request($prepared);

            return !!$result;
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    private function getThumbsFromSet($set)
    {
        $set = $set ? $set->values() : [];

        $thumbs = [];
        foreach ($set as $userGuid) {
            $thumbs[] = (string) $userGuid->value();
        }

        return $thumbs;
    }

    /**
     * @param $inputs
     * @param int $calls
     * @return array
     */
    protected function permutateString($string, $calls = 0)
    {
        $parts = explode(' ', $string);
        $lr = [];
        $rl = [];

        foreach ($parts as $part) {
            $lr[] = end($lr) . "$part ";
        }

        foreach (array_reverse($parts) as $part) {
            $rl[] = "$part " . end($rl);
        }

        $result = array_merge($lr, $rl);

        return $result;
    }
}
