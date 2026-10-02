<?php
ob_start();
$this_file = str_replace('\\', '/', __File__) ;
$doc_root = $_SERVER['DOCUMENT_ROOT'];

$web_root =  str_replace (array($doc_root, "bind/con.php") , '' , $this_file);
$server_root = str_replace ('meet/con.php' ,'', $this_file);

define ('web_root' , $web_root);
define('server_root' , $server_root);

error_reporting(0);
date_default_timezone_set('Africa/Freetown');

//PDO Connection 
$db = new PDO('mysql:host=localhost;dbname=DB_NAME;charset=utf8', 'DB_USER', 'DB_PASSWORD');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query('SET SESSION SQL_BIG_SELECTS=1');

//2and PDO CONNECTION

    $db_username = 'DB_USER';
	$db_password = 'DB_PASSWORD';
	$conn = new PDO( 'mysql:host=localhost;dbname=DB_NAME', $db_username, $db_password );
	if(!$conn){
		die("Fatal Error: Connection Failed!");
	}
	
ob_end_flush();
?>
