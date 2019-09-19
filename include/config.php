<?php
define("TYPE", "mysql"); // pgsql or mysql
define("DB_HOST", "localhost"); // localhost or ip
define("DB_USER", "username"); // username database
define("DB_PASSWORD", "password"); // password database
define("DB_DATABASE", "database"); // table database
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'connect.php');
?>