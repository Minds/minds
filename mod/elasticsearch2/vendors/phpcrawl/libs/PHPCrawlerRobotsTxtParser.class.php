<?php
/**
 * Class for parsing robots.txt-files.
 *
 * @package phpcrawl
 * @internal
 */  
class PHPCrawlerRobotsTxtParser
{
  /**
   * A PHPCrawlerHTTPRequest-object for requesting robots.txt-files.
   *
   * @var PHPCrawlerHTTPRequest
   */
  protected $PageRequest;
  
  public function __construct()
  {
    // Init PageRequest-class
    if (!class_exists("PHPCrawlerHTTPRequest")) include_once($classpath."/PHPCrawlerHTTPRequest.class.php");
    $this->PageRequest = new PHPCrawlerHTTPRequest();
  }
  
  /**
   * Parses the robots.txt-file related to the given URL and returns regular-expression-rules
   * corresponding to the containing "disallow"-rules that are adressed to the given user-agent.
   *
   * @param PHPCrawlerURLDescriptor $Url The URL
   * @param string $user_agent_string User-agent.
   *
   * @return array Numeric array containing regular-expressions for each "disallow"-rule defined in the robots.txt-file
   *               that's adressed to the given user-agent.
   */
  public function parseRobotsTxt(PHPCrawlerURLDescriptor $Url, $user_agent_string)
  {
    PHPCrawlerBenchmark::start("processing_robotstxt");
    
    // URL of robots-txt
    $RobotsTxtUrl = self::getRobotsTxtURL($Url);
    
    // Get robots.txt-content related to the given URL
    $robots_txt_content = $this->getRobotsTxtContent($RobotsTxtUrl);
    
    $non_follow_reg_exps = array();
    
    // If content was found
    if ($robots_txt_content != null)
    {
      // Get all lines in the robots.txt-content that are adressed to our user-agent.
      $applying_lines = $this->getApplyingLines($robots_txt_content, $user_agent_string);
      
      // Get valid reg-expressions for the given disallow-pathes.
      $non_follow_reg_exps = $this->buildRegExpressions($applying_lines, PHPCrawlerUtils::getRootUrl($Url->url_rebuild));
    }
    
    PHPCrawlerBenchmark::stop("processing_robots.txt");
    
    return $non_follow_reg_exps;
  }
  
  /**
   * Function returns all RAW lines in the given robots.txt-content that apply to
   * the given useragent-string.
   *
   * @return array Numeric array with found lines
   */
  protected function getApplyingLines(&$robots_txt_content, $user_agent_string)
  {
    // Split the content into its lines
    $robotstxt_lines = explode("\n", $robots_txt_content);
    
    // Flag that will get TRUE if the loop over the lines gets
    // into a section that applies to our user_agent_string 
    $matching_section = false;
    
    // Flag that indicats if the loop is in a "agent-define-section"
    // (the parts/blocks that contain the "User-agent"-lines.)
    $agent_define_section = false;
    
    // Flag that indicates if we have found a section that fits to our
    // User-agent
    $matching_section_found = false;
    
    // Array to collect all the lines that applie to our user_agent
    $applying_lines = array();
    
    // Loop over the lines
    $cnt = count($robotstxt_lines);
    for ($x=0; $x<$cnt; $x++)
    {
      $robotstxt_lines[$x] = trim($robotstxt_lines[$x]);
      
      // Check if a line begins with "User-agent"
      if (preg_match("#^User-agent:# i", $robotstxt_lines[$x]))
      {
        // If a new "user-agent" section begins -> reset matching_section-flag
        if ($agent_define_section == false)
        {
          $matching_section = false;
        }
        
        $agent_define_section = true; // Now we are in an agent-define-section
        
        // The user-agent specified in the "User-agent"-line
        preg_match("#^User-agent:[ ]*(.*)$# i", $robotstxt_lines[$x], $match);
        $user_agent_section = trim($match[1]);
        
        // if the specified user-agent in the line fits to our user-agent-String (* fits always)
        // -> switch the flag "matching_section" to true
        if ($user_agent_section == "*" || preg_match("#^".preg_quote($user_agent_section)."# i", $user_agent_string))
        {
          $matching_section = true;
          $matching_section_found = true;
        }
        
        continue; // Don't do anything else with the "User-agent"-lines, just go on
      }
      else
      {
        // We are not in an agent-define-section (anymore)
        $agent_define_section = false;
      }
      
      // If we are in a section that applies to our user_agent
      // -> store the line.
      if ($matching_section == true)
      {
        $applying_lines[] = $robotstxt_lines[$x];
      }
      
      // If we are NOT in a matching section (anymore) AND we've already found
      // and parsed a matching section -> stop looking further (thats what RFC says)
      if ($matching_section == false && $matching_section_found == true)
      {
        // break;
      }
    }
    
    return $applying_lines;
  }
  
  /**
   * Returns an array containig regular-expressions corresponding
   * to the given robots.txt-style "Disallow"-lines
   *
   * @param array &$applying_lines Numeric array containing "disallow"-lines.
   * @param string $base_url       Base-URL the robots.txt-file was found in.
   *
   * @return array  Numeric array containing regular-expresseions created for each "disallow"-line.
   */
  protected function buildRegExpressions(&$applying_lines, $base_url)
  { 
    // First, get all "Disallow:"-pathes
    $disallow_pathes = array();
    for ($x=0; $x<count($applying_lines); $x++)
    {
      if (preg_match("#^Disallow:# i", $applying_lines[$x]))
      {
        preg_match("#^Disallow:[ ]*(.*)#", $applying_lines[$x], $match);
        $disallow_pathes[] = trim($match[1]);
      }
    }
    
    // Works like this:
    // The base-url is http://www.foo.com.
    // The driective says: "Disallow: /bla/"
    // This means: The nonFollowMatch is "#^http://www\.foo\.com/bla/#"
    
    $normalized_base_url = PHPCrawlerUtils::normalizeURL($base_url);
    
    $non_follow_expressions = array();
    
    for ($x=0; $x<count($disallow_pathes); $x++)
    {
      // If the disallow-path is empty -> simply ignore it
      if ($disallow_pathes[$x] == "") continue;
      
      $non_follow_path_complpete = $normalized_base_url.substr($disallow_pathes[$x], 1); // "http://www.foo.com/bla/"
      $non_follow_exp = preg_quote($non_follow_path_complpete, "#"); // "http://www\.foo\.com/bla/"
      $non_follow_exp = "#^".$non_follow_exp."#"; // "#^http://www\.foo\.com/bla/#"
        
      $non_follow_expressions[] = $non_follow_exp;
    }
    
    return $non_follow_expressions;
  }
  
  /**
   * Retreives the content of a robots.txt-file
   *
   * @param PHPCrawlerURLDescriptor $Url The URL of the robots.txt-file
   * @return string The content of the robots.txt or NULL if no robots.txt was found.
   */
  protected function getRobotsTxtContent(PHPCrawlerURLDescriptor $Url)
  {
    // Request robots-txt
    $this->PageRequest->setUrl($Url);
    $PageInfo = $this->PageRequest->sendRequest();

    // Return content of the robots.txt-file if it was found, otherwie
    // reutrn NULL
    if ($PageInfo->http_status_code == 200)
    {
      return $PageInfo->content;
    }
    else
    {
      return null;
    }
  }
  
  /** 
   * Returns the Robots.txt-URL related to the given URL
   *
   * @param PHPCrawlerURLDescriptor $Url  The URL as PHPCrawlerURLDescriptor-object
   * @return PHPCrawlerURLDescriptor Url of the related to the passed URL.
   */
  public static function getRobotsTxtURL(PHPCrawlerURLDescriptor $Url)
  {
    $url_parts = PHPCrawlerUtils::splitURL($Url->url_rebuild); 
    $robots_txt_url = $url_parts["protocol"].$url_parts["host"].":".$url_parts["port"] . "/robots.txt";
    
    return new PHPCrawlerURLDescriptor($robots_txt_url);
  }
}
  
?>