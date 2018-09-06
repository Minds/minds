<?php
namespace Minds\Traits;

/**
 * Add interval common methods and properties
 * @author Martin Alejandro Santangelo
 */
trait Interval
{
    /** @var mixed */
    protected $from = null;

    /** @var mixed */
    protected $to = null;

    /**
     * Set the start of the interval
     *
     * @param mixed $from unix timestamp
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Set the end of the interval
     *
     * @param mixed $to unix timestamp
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Get the start of the interval
     *
     * @return $this
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get the end of the interval
     *
     * @return $this
     */
    public function getTo()
    {
        return $this->to;
    }
}