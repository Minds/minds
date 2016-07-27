<?php
namespace Minds\Interfaces;

/**
 * Interface for Analytics Metric
 */
interface AnalyticsMetric
{
    public function setNamespace($namespace);
    public function setKey($key);

    /**
     * Increments the metric
     * @return boolean
     */
    public function increment();

    /**
     * Return a set of metrics for a timespan
     * @param int    $span - eg. 3 (will return 3 units, eg 3 day, 3 months)
     * @param string $unit - eg. day, month, year
     * @param int    $timestamp (optional) - sets the base to work off
     * @return array
     */
    public function get($span, $unit, $timestamp = null);

    /**
     * Return the metric total
     * @return int
     */
    public function total();
}
