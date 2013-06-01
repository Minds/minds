<?php
/**
 * Contains all possible errorcodes for errors that may appear during a http-request.
 *
 * @package phpcrawl.enums
 */
class PHPCrawlerRequestErrors
{
  /**
   * Error-Code: SSL/HTTPS not supported (probably openssl-extension not installed)
   */
  const ERROR_SSL_NOT_SUPPORTED = 1;
  
  /**
   * Error-Code: Host not reachable
   */
  const ERROR_HOST_UNREACHABLE = 2;
  
  /**
   * Error-Code: Host didn't respond with a valid HTTP-header.
   */
  const ERROR_NO_HTTP_HEADER = 3;
  
  /**
   * Error-Code: Could not write or create TMP-file.
   */
  const ERROR_TMP_FILE_NOT_WRITEABLE = 4;
  
  /**
   * Error-Code: Socket timed out while reading data.
   */
  const ERROR_SOCKET_TIMEOUT = 5;
  
 /**
  * Error-Code: Proxy not reachable
  */
  const ERROR_PROXY_UNREACHABLE = 6;
}