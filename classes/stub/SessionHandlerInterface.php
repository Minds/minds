<?php

/**
 * SessionHandlerInterface interface
 *
 * PHP 5.3 compatibility for PHP 5.4's \SessionHandlerInterface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @link http://www.php.net/manual/en/class.sessionhandlerinterface.php
 */
interface SessionHandlerInterface {
    
    public function close();
    public function destroy($session_id);
    public function gc($maxlifetime );
    public function open($save_path , $name );
    public function read($session_id );
    public function write($session_id , $session_data);
    
}