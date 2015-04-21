<?php
class Database
{
    private static $dbName = 'CIS355cjspiece' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'CIS355cjspiece';
    private static $dbUserPassword = 'risingsun';
     
    private static $cont  = null;
     
    public function __construct() 
	{
        die('Init function is not allowed');
    }
     
	public static function connect()
	{
		// One connection through whole application
		if ( null == self::$cont )
		{     
			try
			{
				// Since this program is working with both Japanese and English text the encoding will be UTF8 and thus the PDO connection must be configured appropriately to handle this
				// This was a troublesome issue to troubleshoot until I found a solution here: http://stackoverflow.com/questions/4777900/how-to-display-utf-8-characters-in-phpmyadmin
				self::$cont = new PDO('mysql:host=' . self::$dbHost.';'."dbname=". self::$dbName . ';charset=UTF8', self::$dbUsername, self::$dbUserPassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			}
			catch(PDOException $e)
			{
				die($e->getMessage()); 
			}
		}
		return self::$cont;
	}
     
    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>