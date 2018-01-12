<?php
namespace Minds\Core\Rewards\Contributions;

class Contribution
{

    protected $metric;
    protected $timestamp;
    protected $amount = 0;
    protected $score = 0;
    protected $user;

    /**
     * 
     */
    public function setMetric($metric)
    {
        $this->metric = $metric;
        return $this;
    }

    public function getMetric()
    {
        return $this->metric;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp ?: time() * 1000;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function export() {
        return [
            'metric' => $this->metric,
            'timestamp' => $this->timestamp,
            'amount' => $this->amount,
            'score' => $this->score,
            'user' => $this->user
        ];
    }
}
