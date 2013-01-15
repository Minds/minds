<?php
/**
 * Possible cache-types for caching found URLs within the phpcrawl-system.
 *
 * @package phpcrawl.enums
 */
class PHPCrawlerUrlCacheTypes
{
  /**
   * URLs get cached in local RAM. Best performance.
   */
  const URLCACHE_MEMORY = 1;
  
  /**
   * URLs get cached in a SQLite-database-file. Recommended for spidering huge websites.
   */
  const URLCACHE_SQLITE = 2;
}