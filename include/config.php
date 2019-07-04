<?php
define("TYPE", "pgsql"); // pgsql or mysql
define("DB_HOST", "localhost"); // localhost or ip
define("DB_USER", "radius"); // username database
define("DB_PASSWORD", "radius1234"); // password database
define("DB_DATABASE", "radius"); // table database
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'connect.php');
?>