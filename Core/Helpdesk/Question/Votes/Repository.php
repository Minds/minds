<?php
/**
 * Helpdesk votes repository
 */
namespace Minds\Core\Helpdesk\Question\Votes;

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

    public function __construct(\PDO $db = null, Category\Repository $categoryRepository = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
        $this->repository = $categoryRepository ?: Di::_()->get('Helpdesk\Category\Repository');
    }

    /**
     * 
     * @param array $opts
     * @return Question[]
     */
    public function getList(array $opts = [])
    {
        
    }

    /**
     * Return a vote
     * @param string $question_uuid
     * @param string $user_guid
     * @return Vote
     */
    public function get($question_uuid, $user_guid)
    {
        
    }

    /**
     * Add a vote
     *
     * @param Vote $vote
     * @return string|false
     */
    public function add(Vote $vote)
    {

        $query = "UPSERT INTO helpdesk_votes
            (question_uuid, user_guid, direction) 
            VALUES 
            (?,?,?)";

        $values = [
            $vote->getQuestionUuid(),
            $vote->getUserGuid(),
            $vote->getDirection(),
        ];

        try {
            $statement = $this->db->prepare($query);

            $statement->execute($values);

            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log(print_r($e, true));
            return false;
        }
    }

    /**
     * 
     */
    public function update()
    {
    }

    /**
     * Delete a question
     *
     * @param string $question_uuid
     * @return void
     */
    public function delete(Vote $vote)
    {
        $query = "DELETE FROM helpdesk_votes
            WHERE question_uuid = ?
                AND user_guid = ?  ";

        $values = [
            $vote->getQuestionUuid(),
            $vote->getUserGuid(),
        ];

        try {
            $statement = $this->db->prepare($query);

            return $statement->execute($values);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

}
