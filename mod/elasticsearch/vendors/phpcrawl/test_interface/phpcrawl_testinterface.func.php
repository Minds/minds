<?php
###################################################
#
# Some Function used for the phpcrawl testinterface
#
###################################################


// Function "loads" a setup from a setup-file (serialized array).

function &load_setup ($path, $filename, &$error_ref) {

  // Check the filename
  if (!preg_match("/^[a-z0-9_]*$/ i", $filename)) {
    $error = "An error occured.";
  }
  else {
    $filename = $filename.".psf";
    $fp = @fopen ($path.$filename, "r");
    if ($fp==false) $error_ref = "Couldn't read setup file.";
    else {
      $serialized_array = fread ($fp, filesize ($path.$filename));
      $setup_array = unserialize($serialized_array);
    }
    @fclose ($fp);
  }
  
  return $setup_array;
}

// Function saves a setup (array) to a file. (serialize)

function save_setup ($path, $filename, $setup_array) {

  // Check given filename
  if (!preg_match("/^[a-z0-9_]{1,}$/ i", $filename)) {
    $error = "Given filename is not valid, please only use characters, numbers and '_'.";
  }
  else $filename = $filename.".psf";

  if (!isset($error)) {
    // serialize array
    $setup_array_serialized = serialize($setup_array);
    
    // Write serialized array to file
    $fp = @fopen($path.$filename, "w");
    if ($fp==false) $write_error = true;
    else {
      $check = @fwrite($fp, $setup_array_serialized);
      if ($check==false) $write_error=true;
    }
    @fclose($fp);
  
    if (isset($write_error))
      $error = "Couldn't write setup-file, please check the config.<br>(Is this script allowed to write to the setup-path?)";
  }
  
  if (isset($error))
    return $error;
}

// Function deletes a setup-sile.

function delete_setup ($path, $filename) {

  // Check given filename
  if (!preg_match("/^[a-z0-9_]*$/ i", $filename)) {
    $error = "An error occured.";
  }
  else $filename = $filename.".psf";
  
  if (!isset($error)) {
    $check = @unlink($path.$filename);
    if ($check == false) $error = "Setup-file couldn't be deleted.";
  }
  
  if (isset($error))
    return $error;
}

// Function returns all available setup-files in the specified path.

function get_setup_files($path) {

  $fp = @opendir($path);
  if ($fp) {
    while ($file=readdir($fp)) {
      if (preg_match("/\.psf$/", $file)) $setup_files[] = preg_replace("/\.psf$/", "", $file);
    }
    if (isset($setup_files)) sort($setup_files);
  }
  @closedir($fp);
  
  if (isset($setup_files))
    return $setup_files;
}

// Function

function auth_login($auth_uname, $auth_pw)
{
		// If no username and password was set in the conf
		if ($auth_uname == "" || $auth_pw == "")
		{
		  echo "Login for phpcrawl-testinterface not configured yet.<br>
		        Please choose a login-username and password and put it into
		        the configurartion-file \"phpcrawl_testinterface.conf.php\"<br>
		        located in the test-interface path.";
		  exit;
		}
		// If uname and/or passwod wasnt't typed in or is wrong
		elseif (!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"])
		        || $_SERVER["PHP_AUTH_USER"] != $auth_uname
		        || $_SERVER["PHP_AUTH_PW"] != $auth_pw)
		{
		  header('WWW-Authenticate: Basic realm="phpcrawl-testinterface"');
		  header('HTTP/1.0 401 Unauthorized');
		  exit;
		}
}
?>