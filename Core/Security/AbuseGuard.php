<?php
/**
 * Abuse Guard - Stop spam and trolls
 */
namespace Minds\Core\Security;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Security\AbuseGuard\AccusedEntity;
use Minds\Core\Security\AbuseGuard\Aggregates;
use Minds\Core\Security\AbuseGuard\Ban;
use Minds\Core\Security\AbuseGuard\Recover;

class AbuseGuard
{

    private $start = 0;
    private $end = 0;
    private $accused = [];
    private $score = 15;

    public function __construct($aggregates = null)
    {
        $this->aggregates = $aggregates ?: new Aggregates();
        $this->start = time() - (60 * 10);
        $this->end = time();
    }

    public function setPeriod($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
        return $this;
    }

    public function getScores()
    {
        $this->aggregates->setPeriod($this->start, $this->end);
        $metrics = $this->aggregates->fetch();
        foreach ($metrics as $metric => $rows) {
            foreach ($rows as $row) {
                //@todo use an Aggregate class
                $user_guid = $row['guid'];
                $count = $row['count'];
                if (!isset($this->accused[$user_guid])) {
                    $this->accused[$user_guid] = new AccusedEntity();
                    $this->accused[$user_guid]->setUserGuid($user_guid);
                }
                $this->accused[$user_guid]->setMetric($metric, $count);
            }
        }
        return $this;
    }

    /**
     * Ban all users who have a score over 15
     * @return $this
     */
    public function ban()
    {
        foreach ($this->accused as $accused) {
            if ($accused->getScore() >= $this->score) {
                $ban = new Ban();
                $ban->setAccused($accused)
                    ->ban();
            }
        }
        return $this;
    }

    /**
     * Recover the mess these trolls made
     */
    public function recover()
    {
        foreach ($this->accused as $accused) {
            if ($accused->getScore() >= $this->score) {
                $recover = new Recover();
                $recover->setAccused($accused)
                    ->recover();
            }
        }
        return $this;
    }

    public function getTotalAccused()
    {
        $total = 0;
        foreach ($this->accused as $accused) {
            if ($accused->getScore() >= $this->score) {
                $total++;
            }
        }
        return $total;
    }

    public function getTotal()
    {
        return count($this->accused);
    }

}
