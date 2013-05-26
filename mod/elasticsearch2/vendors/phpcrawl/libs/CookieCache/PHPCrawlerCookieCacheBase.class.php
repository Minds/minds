<?php
/**
 * Abstract baseclass for storing cookies.
 *
 * @package phpcrawl
 * @internal
 */
abstract class PHPCrawlerCookieCacheBase
{
  /**
   * Adds a cookie to the cookie-cache.
   *
   * @param PHPCrawlerCookieDescriptor $Cookie The cookie to add.
   */
  abstract public function addCookie(PHPCrawlerCookieDescriptor $Cookie);
  
  /**
   * Adds a bunch of cookies to the cookie-cache.
   *
   * @param array $cookies  Numeric array conatinin the cookies to add as PHPCrawlerCookieDescriptor-objects
   */
  abstract public function addCookies($cookies);
  
  /**
   * Returns all cookies from the cache that are adressed to the given URL
   *
   * @param string $target_url The target-URL
   * @return array  Numeric array conatining all matching cookies as PHPCrawlerCookieDescriptor-objects
   */
  abstract public function getCookiesForUrl($target_url);
}
?>