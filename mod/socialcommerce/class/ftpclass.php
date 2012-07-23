<?php

class PS_FTPClass {
	var $docroot;
	var $ftproot;
	var $host;
	var $username;
	var $password;
	var $ftpstream;
	var $debug;
	var $port;
	var $image_path;
	/**
	 * Constructor
	 *
	 * The constructor opens a connection to the FTP server
	 * and logins to the server using the specified details.
	 * @param string $docroot The document root of your web server . Example : /home/jatinder/public_html/
	 * @param string $ftproot The document root of your FTP server. Example : /public_html/
	 * @param string $host The domain name of your FTP server
	 * @param string $username FTP server login ID
	 * @param string $password FTP server password
	 */
	 
	function PS_FTPClass($docroot, $ftproot, $port, $host, $username, $password,$httppath) {
		$this->docroot = $docroot;
		$this->ftproot = $ftproot;
		$this->port = $port;
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->debug = false;
		$this->httppath = $httppath;				
		$ftpstream = @ftp_connect($host);
		
		// open connection
		if($port!=""){
			$conn = ftp_connect($host,$port);
		}else{
			$conn = ftp_connect($host);
		}
		if($conn){
			$login = @ftp_login($conn, html_entity_decode($username), html_entity_decode($password));			
			if($login){
				$this->conn = $conn;				
				// IMPORTANT!!! turn passive mode on
				ftp_pasv ( $conn, true );
				return $login;
			}else{				
				@ftp_close($conn);
				return false;
			}
		}else{			
			return false;
		}	
		
	}
	
	/**
	 * Create a folder on the server
	 *
	 * @access public
	 * @param string $pathname Path and name of the folder to create. The path must be realtive to your website root. Example : test
	 * @return bool
	 */
	function f_mkdir($pathname) {
		$conn = $this->conn;
		$this->image_path = $pathname;
		$append_path = $this->ftproot;
		if($conn) {			
			$pathname = @trim($pathname);
			$paths = explode('/',$pathname);
			foreach($paths as $path){
				@ftp_mkdir ($conn,$append_path.$path);				
				@ftp_chmod($conn, 0777, $append_path.$path);	
				$append_path .= "$path/";			
			}
		}	
	}
	function f_nlist(){
		$conn = $this->conn;		
		if($conn) {	
			return ftp_nlist($conn, ".");	
		}
	}
	
	function f_upload($to_image_name,$from_image_name){
		// perform file upload
		$this->ftproot;
		$conn = $this->conn;		
		if($conn) {
			$upload = ftp_put($conn,$this->ftproot."/".$this->image_path."/".$to_image_name, $this->docroot.$from_image_name, FTP_BINARY);
			if($upload){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function f_upload_path($to_image_name,$rootpath_from){
		// perform file upload
		$conn = $this->conn;
		//echo $this->ftproot.$this->image_path."/".$to_image_name;		
		if($conn) {
			$upload = ftp_put($conn,$this->ftproot.$this->image_path."/".$to_image_name, $rootpath_from, FTP_BINARY);
			if($upload){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function open_f_upload_path($to_image_name,$rootpath_from){
		// perform file upload
		$conn = $this->conn;				
		if($conn) {			
			$upload = ftp_nb_put($conn, $this->ftproot.$this->image_path."/".$to_image_name, $rootpath_from, FTP_BINARY);
			while ($upload == FTP_MOREDATA) {
			   // Do whatever you want
			   //echo "Uploaded".".";
			   // Continue uploading...
			   $upload = ftp_nb_continue($conn);
			   //return true;
			}
			if ($upload != FTP_FINISHED) {
			   //echo "There was an error uploading the file...";			   
			   return false;
			}else{
			 return true;
			}
		}else{
			return false;
		}
	}		
	function f_close(){
		// close the FTP stream
		$conn = $this->conn;
		if($conn){
			@ftp_close($conn);
		}		
	}
	function f_unlink($from_image_name){
		/**
		 * Clean up
		 *
		 * @access public
		 * @return bool
		 */
		unlink($this->docroot.$from_image_name);
	}
}
?>
