<?php
define("TYPE", "pgsql");
define("DB_HOST", "localhost");
define("DB_USER", "radius");
define("DB_PASSWORD", "radius@p3l3m");
define("DB_DATABASE", "radius");
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'connect.php');
?>