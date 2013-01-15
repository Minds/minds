<?php
/**
 * Static util-methods used by phpcrawl.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerUtils
{
  /**
   * Splits an URL into its parts
   *
   * @param string $url  The URL
   * @return array       An array containig the parts of the URL
   *
   *                     The keys are:
   *
   *                     "protocol" (z.B. "http://")
   *                     "host"     (z.B. "www.bla.de")
   *                     "path"     (z.B. "/test/palimm/")
   *                     "file"     (z.B. "index.htm")
   *                     "domain"   (z.B. "foo.com")
   *                     "port"     (z.B. 80)
   *                     "auth_username"
   *                     "auth_password"
   */
  public static function splitURL($url)
  {
    // Protokoll der URL hinzufügen (da ansonsten parse_url nicht klarkommt)
    if (!preg_match("#^[a-z]+://# i", $url))
      $url = "http://" . $url;
    
    $parts = @parse_url($url);
    
    if (!isset($parts)) return null;
    
    $protocol = $parts["scheme"]."://";
    $host = (isset($parts["host"]) ? $parts["host"] : "");
    $path = (isset($parts["path"]) ? $parts["path"] : "");
    $query = (isset($parts["query"]) ? "?".$parts["query"] : "");
    $auth_username = (isset($parts["user"]) ? $parts["user"] : "");
    $auth_password = (isset($parts["pass"]) ? $parts["pass"] : "");
    $port = (isset($parts["port"]) ? $parts["port"] : "");
    
    // File
    preg_match("#^(.*/)([^/]*)$#", $path, $match); // Alles ab dem letzten "/"
    if (isset($match[0]))
    {
      $file = trim($match[2]);
      $path = trim($match[1]);
    }
    else
    {
      $file = "";
    }
      
    // Der Domainname aus dem Host
    // Host: www.foo.com -> Domain: foo.com
    $parts = @explode(".", $host);
    if (count($parts) <= 2)
    {
      $domain = $host;
    }
    else if (preg_match("#^[0-9]+$#", str_replace(".", "", $host))) // IP
    {
      $domain = $host;
    }
    else
    {
      $pos = strpos($host, ".");
      $domain = substr($host, $pos+1);
    }
    
    // DEFAULT VALUES für protocol, path, port etc. (wenn noch nicht gesetzt)
      
    // Wenn Protokoll leer -> Protokoll ist "http://"
    if ($protocol == "") $protocol="http://";
    
    // Wenn Port leer -> Port setzen auf 80 or 443
    // (abhängig vom Protokoll)
    if ($port == "")
    {
      if (strtolower($protocol) == "http://") $port=80;
      if (strtolower($protocol) == "https://") $port=443;
    }
    
    // Wenn Pfad leet -> Pfad ist "/"
    if ($path=="") $path = "/";
    
    // Rückgabe-Array
    $url_parts["protocol"] = $protocol;
    $url_parts["host"] = $host;
    $url_parts["path"] = $path;
    $url_parts["file"] = $file;
    $url_parts["query"] = $query;
    $url_parts["domain"] = $domain;
    $url_parts["port"] = $port;
    
    $url_parts["auth_username"] = $auth_username;
    $url_parts["auth_password"] = $auth_password;
    
    return $url_parts;
  }
  
  /**
   * Builds an URL from it's single parts.
   *
   * @param array $url_parts Array conatining the URL-parts.
   *                         The keys should be:
   *
   *                         "protocol" (z.B. "http://") OPTIONAL
   *                         "host"     (z.B. "www.bla.de")
   *                         "path"     (z.B. "/test/palimm/") OPTIONAL
   *                         "file"     (z.B. "index.htm") OPTIONAL
   *                         "port"     (z.B. 80) OPTIONAL
   *                         "auth_username" OPTIONAL
   *                         "auth_password" OPTIONAL
   * @param bool $normalize   If TRUE, the URL will be returned normalized.
   *                          (I.e. http://www.foo.com/path/ insetad of http://www.foo.com:80/path/)
   * @return string The URL
   *                         
   */
  public static function buildURLFromParts($url_parts, $normalize = false)
  {
    // Host has to be set aat least
    if (!isset($url_parts["host"]))
    {
      throw new Exception("Cannot generate URL, host not specified!");
    }
    
    if (!isset($url_parts["protocol"]) || $url_parts["protocol"] == "") $url_parts["protocol"] = "http://";
    if (!isset($url_parts["port"])) $url_parts["port"]= 80;
    if (!isset($url_parts["path"])) $url_parts["path"] = "";
    if (!isset($url_parts["file"])) $url_parts["file"] = "";
    if (!isset($url_parts["query"])) $url_parts["query"]= "";
    if (!isset($url_parts["auth_username"])) $url_parts["auth_username"]= "";
    if (!isset($url_parts["auth_password"])) $url_parts["auth_password"]= "";
    
    // Autentication-part
    $auth_part = "";
    if ($url_parts["auth_username"] != "" && $url_parts["auth_password"] != "")
    {
      $auth_part = $url_parts["auth_username"].":".$url_parts["auth_password"]."@";
    }
    
    // Port-part
    $port_part = ":" . $url_parts["port"];
    
    // Normalize
    if ($normalize == true)
    {
      if ($url_parts["protocol"] == "http://" && $url_parts["port"] == 80 ||
          $url_parts["protocol"] == "https://" && $url_parts["port"] == 443)
      {
        $port_part = "";
      }
      
      // Don't add port to links other than "http://" or "https://"
      if ($url_parts["protocol"] != "http://" && $url_parts["protocol"] != "https://")
      {
        $port_part = "";
      }
    }
    
    // Put together the url
    $url = $url_parts["protocol"] . $auth_part . $url_parts["host"]. $port_part . $url_parts["path"] . $url_parts["file"] . $url_parts["query"];
    
    return $url;
  }
  
  /**
   * Normalizes an URL
   *
   * I.e. converts http://www.foo.com:80/path/ to http://www.foo.com/path/
   *
   * @param string $url
   * @return string OR NULL on failure
   */
  public static function normalizeURL($url)
  {
    $url_parts = self::splitURL($url);
    
    if ($url_parts == null) return null;
    
    $url_normalized = self::buildURLFromParts($url_parts, true);
    return $url_normalized;
  }
  
  /**
   * Checks whether a given RegEx-pattern is valid or not.
   *
   * @return bool
   */
  public static function checkRegexPattern($pattern)
  {
    $check = @preg_match($pattern, "anything"); // thats the easy way to check a pattern ;)
    if (is_integer($check) == false) return false;
    else return true;
  }
  
  /**
   * Gets the HTTP-statuscode from a given response-header.
   *
   * @param string $header  The response-header
   * @return int            The status-code or NULL if no status-code was found.
   */
  public static function getHTTPStatusCode($header)
  {
    $first_line = strtok($header, "\n");
    
    preg_match("# [0-9]{3}#", $first_line, $match);
    
    if (isset($match[0]))
      return (int)trim($match[0]);
    else
      return null;
  }
  
  /**
   * Reconstructs a full qualified and normalized URL from a given link relating to the URL the link was found in.
   *
   * @param string $link          The link (i.e. "../page.htm")
   * @param PHPCrawlerUrlPartsDescriptor $BaseUrlParts  The parts of the URL the link was found in (i.e. "http://www.foo.com/folder/index.html")
   *
   * @return string The rebuild, full qualified and normilazed URL the link is leading to (i.e. "http://www.foo.com/page.htm")
   *                Or NULL if the link couldn't be rebuild correctly.
   */
  public static function buildURLFromLink($link, PHPCrawlerUrlPartsDescriptor $BaseUrlParts)
  { 
    
    $url_parts = $BaseUrlParts->toArray();
    
    // Entities-replacements
    $entities= array ("'&(quot|#34);'i",
                        "'&(amp|#38);'i",
                        "'&(lt|#60);'i",
                        "'&(gt|#62);'i",
                        "'&(nbsp|#160);'i",
                        "'&(iexcl|#161);'i",
                        "'&(cent|#162);'i",
                        "'&(pound|#163);'i",
                        "'&(copy|#169);'i");
                        
    $replace=array ("\"",
                    "&",
                    "<",
                    ">",
                    " ",
                    chr(161),
                    chr(162),
                    chr(163),
                    chr(169));
   
   // Remove "#..." at end, but ONLY at the end,
   // not if # is at the beginning !
   $link = preg_replace("/^(.{1,})#.{0,}$/", "\\1", $link);

   // Cases
   
   // Strange link like "//foo.htm" -> make it to "http://foo.html"
   if (substr($link, 0, 2) == "//")
   {
     $link = "http:".$link;
   }
   
   // 1. relative link starts with "/" --> doc_root
   // "/index.html" -> "http://www.foo.com/index.html"    
   elseif (substr($link,0,1)=="/")
   {
     $link = $url_parts["protocol"].$url_parts["host"].":".$url_parts["port"].$link;
   }
    
    // 2. "./foo.htm" -> "foo.htm"
    elseif (substr($link,0,2)=="./")
    {
      $link=$url_parts["protocol"].$url_parts["host"].":".$url_parts["port"].$url_parts["path"].substr($link, 2);
    }
    
    // 3. Link is an absolute Link with a given protocol and host (f.e. "http://...")
    // DO NOTHING
    elseif (preg_match("#^[a-z0-9]{1,}(:\/\/)# i", $link))
    {
      $link = $link;
    }
    
    // 4. Link is stuff like "javascript: ..." or something
    elseif (preg_match("/^[a-zA-Z]{0,}:[^\/]{0,1}/", $link))
    {
      $link = "";
    }
    
    // 5. "../../foo.html" -> remove the last path from our actual path
    // and remove "../" from link at the same time until there are
    // no more "../" at the beginning of the link
    elseif (substr($link, 0, 3)=="../")
    {
      $new_path = $url_parts["path"];
      
      while (substr($link, 0, 3) == "../")
      {
        $new_path = preg_replace('/\/[^\/]{0,}\/$/',"/", $new_path);
        $link  = substr($link, 3);
      }
      
      $link = $url_parts["protocol"].$url_parts["host"].":".$url_parts["port"].$new_path.$link;
    }
    
    // 6. link starts with #
    // -> leads to the same site as we are on, trash
    elseif (substr($link,0,1) == "#")
    {
      $link="";
    }
    
    // 7. link starts with "?"
    elseif (substr($link,0,1)=="?")
    {
      $link = $url_parts["protocol"].$url_parts["host"].":".$url_parts["port"].$url_parts["path"].$url_parts["file"].$link;
    }
    
    // 7. thats it, else the abs_path is simply PATH.LINK ...
    else
    { 
      $link = $url_parts["protocol"].$url_parts["host"].":".$url_parts["port"].$url_parts["path"].$link;
    }
    
    if ($link == "") return null;

    
    // Now, at least, replace all HTMLENTITIES with normal text !!
    // Fe: HTML-Code of the link is: <a href="index.php?x=1&amp;y=2">
    // -> Link has to be "index.php?x=1&y=2"
    $link = preg_replace($entities, $replace, $link);
    
    // Replace linebreaks in the link with "" (happens if a links in the sourcecode
    // linebreaks)
    $link = str_replace(array("\n", "\r"), "", $link);
    
    // "Normalize" URL
    $link = self::normalizeUrl($link);
        
    return $link;
  }
  
  /**
   * Returns the base-URL specified in a meta-tag in the given HTML-source
   *
   * @return string The base-URL or NULL if not found.
   */
  public static function getBaseUrlFromMetaTag(&$html_source)
  {
    preg_match("#<{1}[ ]{0,}((?i)base){1}[ ]{1,}((?i)href|src)[ ]{0,}=[ ]{0,}(\"|'){0,1}([^\"'><\n ]{0,})(\"|'|>|<|\n| )# i", $html_source, $match);
    
    if (isset($match[4]))
    {
      $match[4] = trim($match[4]);
      return $match[4];
    }
    else return null;
  }
  
  /**
   * Returns the redirect-URL from the given HTML-header
   *
   * @return string The redirect-URL or NULL if not found.
   */
  public static function getRedirectURLFromHeader(&$header)
  {
    // Get redirect-link from header
    preg_match("/((?i)location:|content-location:)(.{0,})[\n]/", $header, $match);
    
    if (isset($match[2]))
    {
      $redirect = trim($match[2]);
      return $redirect;
    }
    else return null;
  }
  
  /**
   * Checks whether a given string matches with one of the given regular-expressions.
   *
   * @param &string $string      The string
   * @param array   $regex_array Numerich array containing the regular-expressions to check against.
   *
   * @return bool TRUE if one of the regexes matches the string, otherwise FALSE.
   */
  public static function checkStringAgainstRegexArray(&$string, $regex_array)
  {
    if (count($regex_array) == 0) return true;
    
    $cnt = count($regex_array);
    for ($x=0; $x<$cnt; $x++)
    {
      if (preg_match($regex_array[$x], $string))
      {
        return true;
      }
    }
    
    return false;
  }
  
  /**
   * Gets the value of an header-directive from the given HTTP-header.
   *
   * Example:
   * <code>PHPCrawlerUtils::getHeaderValue($header, "content-type");</code>
   *
   * @param string $header    The HTTP-header
   * @param string $directive The header-directive
   *
   * @return string The value of the given directive found in the header.
   *                Or NULL if not found.
   */
  public static function getHeaderValue($header, $directive)
  {
    preg_match("#[\r\n]".$directive.":(.*)[\r\n\;]# Ui", $header, $match);
    
    if (isset($match[1]) && trim($match[1]) != "")
    {
      return trim($match[1]);
    }
    
    else return null;
  }
  
  /**
   * Returns all cookies from the give response-header.
   *
   * @param string $header      The response-header
   * @param string $source_url  URL the cookie was send from.
   * @return array Numeric array containing all cookies as PHPCrawlerCookieDescriptor-objects.
   */
  public static function getCookiesFromHeader($header, $source_url)
  {
    $cookies = array();
    
    $hits = preg_match_all("#[\r\n]set-cookie:(.*)[\r\n]# Ui", $header, $matches);
    
    if ($hits && $hits != 0)
    {
      for ($x=0; $x<count($matches[1]); $x++)
      {
        $cookies[] = PHPCrawlerCookieDescriptor::getFromHeaderLine($matches[1][$x], $source_url);
      }
    }
    
    return $cookies;
  }
  
  /**
   * Returns the normalized root-URL of the given URL
   *
   * @param string $url The URL, e.g. "www.foo.con/something/index.html"
   * @return string The root-URL, e.g. "http://www.foo.com"
   */
  public static function getRootUrl($url)
  {
    $url_parts = self::splitURL($url);
    $root_url = $url_parts["protocol"].$url_parts["host"].":".$url_parts["port"];
    
    return self::normalizeURL($root_url);
  }
  
  /**
   * Deletes a directory recursivly
   */
  public static function rmDir($dir)
  {
    if (is_dir($dir))
    {
      $objects = scandir($dir);
      foreach ($objects as $object)
      {
        if ($object != "." && $object != "..")
        {
          if (filetype($dir.DIRECTORY_SEPARATOR.$object) == "dir")
            self::rmDir($dir.DIRECTORY_SEPARATOR.$object);
          else
            unlink($dir.DIRECTORY_SEPARATOR.$object);
        }
      }
      reset($objects);
      
      rmdir($dir);
    }
  } 
  
  /**
   * Serializes data (objects, arrayse etc.) and writes it to the given file.
   */
  public static function serializeToFile($target_file, $data)
  {
    $serialized_data = serialize($data);
    file_put_contents($target_file, $serialized_data);
  }
  
  /**
   * Returns deserialized data that is stored in a file.
   *
   * @param string $file The file containing the serialized data
   *
   * @return mixed The data or NULL if the file doesn't exist
   */
  public static function deserializeFromFile($file)
  {
    if (file_exists($file))
    {
      $serialized_data = file_get_contents($file);
      return unserialize($serialized_data);
    }
    else return null;
  }
  
  /**
   * Sorts a twodimensiolnal array.
   */
  public static function sort2dArray(&$array, $sort_args)
  {
    $args = func_get_args();
    
    // Für jedes zu sortierende Feld ein eigenes Array bilden
    @reset($array);
    while (list($field) = @each($array)) 
    {
      for ($x=1; $x<count($args); $x++)
      {
        // Ist das Argument ein String, sprich ein Sortier-Feld?
        if (is_string($args[$x]))
        {
          $value = $array[$field][$args[$x]];
          
          ${$args[$x]}[] = $value;
        }
      }
    }

    // Argumente für array_multisort bilden
    for ($x=1; $x<count($args); $x++)
    {
      if (is_string($args[$x]))
      {
        // Argument ist ein TMP-Array
        $params[] = &${$args[$x]};
      }
      else
      {
        // Argument ist ein Sort-Flag so wie z.B. "SORT_ASC"
        $params[] = &$args[$x];
      }
    }
    
    // Der letzte Parameter ist immer das zu sortierende Array (Referenz!)
    $params[] = &$array;

    // Array sortieren
    call_user_func_array("array_multisort", $params);
    
    @reset($array);
  }
  
  /**
   * Determinates the systems temporary-directory.
   *
   * @return string
   */
  public static function getSystemTempDir()
  {
    $tmpfile = tempnam("dummy","");
    $path = dirname($tmpfile);
    unlink($tmpfile);
    
    return $path."/";
  }
  
  /**
   * Gets all meta-tag atteributes from the given HTML-source.
   *
   * @param &string &$html_source
   * @return array Assoziative array conatining all found meta-attributes.
   *               The keys are the meta-names, the values the content of the attributes.
   *               (like $tags["robots"] = "nofollow")
   *
   */
  public static function getMetaTagAttributes(&$html_source)
  {                
    preg_match_all("#<\s*meta\s+".
                   "name\s*=\s*(?|\"([^\"]+)\"|'([^']+)'|([^\s><'\"]+))\s+".
                   "content\s*=\s*(?|\"([^\"]+)\"|'([^']+)'|([^\s><'\"]+))".
                   ".*># Uis", $html_source, $matches);
    
    $tags = array();            
    for ($x=0; $x<count($matches[0]); $x++)
    {
      $meta_name = strtolower(trim($matches[1][$x]));
      $meta_value = strtolower(trim($matches[2][$x]));
      
      $tags[$meta_name] = $meta_value;
    }
    
    return $tags;
  }
  
  /**
   * Checks wether the given string is an UTF8-encoded string.
   *
   * Taken from http://www.php.net/manual/de/function.mb-detect-encoding.php
   * (comment from "prgss at bk dot ru")
   * 
   * @param string $string The string
   * @return bool TRUE if the string is UTF-8 encoded.
   */
  public static function isUTF8String($string)
  { 
    $sample = @iconv('utf-8', 'utf-8', $string);
    
    if (md5($sample) == md5($string))
      return true;
    else
      return false;
  }
  
  /**
   * Checks whether the given string is a valid, urlencoded URL (by RFC)
   * 
   * @param string $string The string
   * @return bool TRUE if the string is a valid url-string.
   */
  public static function isValidUrlString($string)
  { 
    if (preg_match("#^[a-z0-9/.&=?%-_.!~*'()]+$# i", $string)) return true;
    else return false;
  }
}
?>