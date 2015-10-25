<?php
/**
 * Analytics Metric Interface
 */
namespace Minds\Interfaces;

interface AnalyticsMetric{

  public function setNamespace($namespace);
  public function setKey($key);

  /**
   * Increments the analytics
   * @return boolean
   */
  public function increment();

  /**
   * Return a set of analytics for a timespan
   * @param int $span - eg. 3 (will return 3 units, eg 3 day, 3 months)
   * @param string $unit - eg. day, month, year
   * @param int $timestamp (optional) - sets the base to work off
   * @return array
   */
  public function get($span, $unit, $timestamp = NULL);

  /**
   * Return the total
   * @return int
   */
  public function total();

}
