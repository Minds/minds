<?php
/**
 * Elgg translation browser.
 * 
 * @package translationbrowser
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Mariusz Bulkowski
 * @author v2 Pedro Prez
 * @copyright 2009
 * @link http://www.pedroprez.com.ar/
 */

/**
 * @return language arrays
 * @desc better and cleaner method to get all the language files
 *       with their absolute paths
 */
function translationbrowser_get_language_files_for_active_plugins()
{

  //the beginning directory is already stored in the configuration
  global $CONFIG;
  $len_dir= strlen($CONFIG->path);
  $results = array(); 
  //all the language files are already installed in global $CONFIG..
  //for every file that matches \w{2}.php  
  $regex = "/^\w{2}.php$/";
  foreach($CONFIG->language_paths as $d => $valid)
  {
        foreach(array_diff(scandir($d), array('.', '..')) as $f)
        {
            //if we're a file and we match the iso_639_1 format like elgg says
            //then we add to the result list and send it up
            if(is_file($d . $f) && (($regex) ? preg_match($regex, $f) : 1))
            {

            $text = str_replace('//','/',"".$d.$f."\n");
            $text = trim(substr($text,$len_dir));
            $module_text = elgg_echo('translationbrowser:undefined');
            if (preg_match('%^mod/([^\\s|^\\/]+)/%', $text, $matches)) 
            {
               if(isset($matches[1]))
                    $module_text = elgg_echo($matches[1]);
               else
                    $module_text = $text;
            } else 
               {
                  if($text == 'languages/en.php')
                        $module_text = elgg_echo('translationbrowser:languagecore');
                  else
                        $module_text = $text;
               }
            
                $results[$module_text]= $d . $f;
            }
        }
  }

  return $results;
}


////////////////////////////////////////////////////////////////////////////////
// return list en.php files with full path
////////////////////////////////////////////////////////////////////////////////
function translationbrowser_scandir($dir,&$tab_out)
{
  global $CONFIG;
  $io=0;

  $len_dir= strlen($CONFIG->path);

  if ($handle = @opendir($dir))
  {
    while (false !== ($file = readdir($handle)))
    {
      if ($file != "." && $file != "..")
      {
        $newdir = "";
        $filetext = "";

        if (!is_file($dir."/".$file) or is_dir($dir) )
        {
          $io++;
          $newdir.= $dir."/".$file."/";
          translationbrowser_scandir($newdir,$tab_out);
          if(is_file($dir.$file)&&($file=="en.php") )
          {
            $text =  str_replace('//','/',"".$dir.$file."\n");
            $text = trim(substr($text,$len_dir));
              
            $module_text = elgg_echo('translationbrowser:undefined');
            if (preg_match('%^mod/([^\\s|^\\/]+)/%', $text, $matches)) 
            {
                                if(isset($matches[1]))
                                        $module_text = elgg_echo($matches[1]);
                                else
                                        $module_text = $text;
            } else 
            {
                if($text == 'languages/en.php')
                    $module_text = elgg_echo('translationbrowser:languagecore');
                else
                                    $module_text = $text;
                        }
            $tab_out[$text]= $module_text;
          }
        }
      }
    }
    closedir($handle);
  }
}
		

////////////////////////////////////////////////////////////////////////////////
// return variable name for language
// i must replace all wrong char from language  
// in variable name i can't use space etc. 
////////////////////////////////////////////////////////////////////////////////
function translationbrowser_clean_lang($lang)
{
  $str = strtolower($lang);
  $t_in = array(" ","(",")");
  $t_out = array("-","-","-");
  $str = str_replace($t_in,$t_out,$str);
  return $str;
}

function translationbrowser_clean_text($text)
{
  $t_in = array('"','&gt;','&lt;','$');
  $t_out = array('\"','>','<',"\$");
  $str = str_replace($t_in,$t_out,$text);
  return $str;
}


////////////////////////////////////////////////////////////////////////////////
// create folders structure
// return true if folder exists or create it
// return false when folder don't exists or can't create it
////////////////////////////////////////////////////////////////////////////////
function translationbrowser_mkdir_recursive($pathname, $mode)
{
  is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname), $mode);
  return is_dir($pathname) || @mkdir($pathname, $mode);
}



?>
