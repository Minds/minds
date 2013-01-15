<?php
/**
 * A static benchmark-class for doing benchmarks within phpcrawl.
 *
 * Example:
 * <code>
 * PHPCrawlerBenchmark::start("benchmark1");
 * sleep(2);
 * PHPCrawlerBenchmark::stop("benchmark1");
 * echo PHPCrawlerBenchmark::getElapsedTime("benchmark1");
 * </code>
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerBenchmark
{
  protected static $benchmark_results = array();
  protected static $benchmark_starttimes = array();
  protected static $benchmark_startcount = array();
  protected static $temporary_benchmarks = array();
  
  /**
   * Starts the clock for the given benchmark.
   *
   * @param string $identifie           The benchmark name/identifier.
   * @param bool   $temporary_benchmark If set to TRUE, the benchmark will not be returned by methods
   *                                    like getAllBenchmarks() and printAllBenchmarks()
   */
  public static function start($identifier, $temporary_benchmark = false)
  {
    self::$benchmark_starttimes[$identifier] = self::getmicrotime();
    
    if (isset(self::$benchmark_startcount[$identifier]))
      self::$benchmark_startcount[$identifier] = self::$benchmark_startcount[$identifier] + 1;
    else self::$benchmark_startcount[$identifier] = 1;
    
    if ($temporary_benchmark == true)
    {
      self::$temporary_benchmarks[$identifier] = true;
    }
  }
  
  public static function getCallCount($identifier)
  {
    return self::$benchmark_startcount[$identifier];
  }
  
  /**
   * Stops the benchmark-clock for the given benchmark.
   *
   * @param string  The benchmark name/identifier.
   */
  public static function stop($identifier)
  {
    if (isset(self::$benchmark_starttimes[$identifier]))
    {
      $elapsed_time = self::getmicrotime() - self::$benchmark_starttimes[$identifier];

      if (isset(self::$benchmark_results[$identifier])) self::$benchmark_results[$identifier] += $elapsed_time;
      else self::$benchmark_results[$identifier] = $elapsed_time;
    }
  }
  
  /**
   * Gets the elapsed time for the given benchmark.
   *
   * @param string  The benchmark name/identifier.
   * @return float The elapsed time in seconds and miliseconds (e.g. 1.74343)
   */
  public static function getElapsedTime($identifier)
  {
    if (isset(self::$benchmark_results[$identifier]))
    {
      return self::$benchmark_results[$identifier];
    }
  }
  
  /**
   * Resets the clock for the given benchmark.
   */
  public static function reset($identifier)
  {
    if (isset(self::$benchmark_results[$identifier]))
    {
      self::$benchmark_results[$identifier] = 0;
    }
  }
  
  /**
   * Resets all clocks for all benchmarks.
   *
   * @param array $retain_benachmarks Optional. Numeric array containing benchmark-identifiers that should NOT get resetted.
   */
  public static function resetAll($retain_benchmarks = array())
  {
    // If no benchmarks should be retained
    if (count($retain_benchmarks) == 0)
    {
      self::$benchmark_results = array();
      return;
    }
    
    // Else reset all benchmarks BUT the retain_benachmarks
    @reset(self::$benchmark_results);
    while (list($identifier) = @each(self::$benchmark_results))
    {
      if (!in_array($identifier, $retain_benchmarks))
      {
        self::$benchmark_results[$identifier] = 0;
      }
    }
  }
  
  public static function printAllBenchmarks($linebreak = "<br />")
  {
    @reset(self::$benchmark_results);
    while (list($identifier, $elapsed_time) = @each(self::$benchmark_results))
    {
      if (!isset(self::$temporary_benchmarks[$identifier])) echo $identifier.": ".$elapsed_time." sec" . $linebreak;
    }
  }
  
  /**
   * Returns all registered benchmark-results.
   *
   * @return array associative Array. The keys are the benchmark-identifiers, the values the benchmark-times.
   */
  public static function getAllBenchmarks()
  {
    $benchmarks = array();
    
    @reset(self::$benchmark_results);
    while (list($identifier, $elapsed_time) = @each(self::$benchmark_results))
    {
      if (!isset(self::$temporary_benchmarks[$identifier])) $benchmarks[$identifier] = $elapsed_time;
    }
    
    return $benchmarks;
  }
  
  /**
   * Returns the current time in seconds and milliseconds.
   *
   * @return float
   */
  public static function getmicrotime()
  { 
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec); 
  }
}