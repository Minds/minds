<?php

/**
 * Minds Comments Votes Repository
 *
 * @author emi
 */

namespace Minds\Core\Comments\Votes;

use Cassandra\Set;
use Cassandra\Type;
use Cassandra\Varint;
use Minds\Core\Comments\Comment;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Votes\Vote;

class Repository
{
    /** @var Client */
    protected $cql;

    /**
     * Repository constructor.
     * @param null $cql
     */
    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Inserts a new vote to the votes set
     * @param Vote $vote
     * @return bool
     */
    public function add(Vote $vote)
    {
        /** @var Comment $comment */
        $comment = $vote->getEntity();

        $field = "votes_{$vote->getDirection()}";
        $set = new Set(Type\Set::varint());
        $set->add(new Varint($vote->getActor()->guid));

        $cql = "UPDATE comments SET {$field} = {$field} + ? WHERE entity_guid = ? AND parent_guid = ? AND guid = ? IF EXISTS";
        $values = [
            $set,
            new Varint($comment->getEntityGuid()),
            new Varint($comment->getParentGuid()),
            new Varint($comment->getGuid()),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        return !!$this->cql->request($prepared);
    }

    /**
     * Deletes a vote from the votes set
     * @param Vote $vote
     * @return bool
     */
    public function delete(Vote $vote)
    {
        /** @var Comment $comment */
        $comment = $vote->getEntity();

        $field = "votes_{$vote->getDirection()}";
        $set = new Set(Type\Set::varint());
        $set->add(new Varint($vote->getActor()->guid));

        $cql = "UPDATE comments SET {$field} = {$field} - ? WHERE entity_guid = ? AND parent_guid = ? AND guid = ? IF EXISTS";
        $values = [
            $set,
            new Varint($comment->getEntityGuid()),
            new Varint($comment->getParentGuid()),
            new Varint($comment->getGuid()),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        return !!$this->cql->request($prepared);
    }
}