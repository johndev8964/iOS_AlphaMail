<?php
// Database class folder path
define( 'DBPATH', dirname(__FILE__) . '/database/' );

// erro reporting
error_reporting( E_ALL );

// get db environment variables
require_once( DBPATH . 'config.php' );

// include all databse classes
require_once( DBPATH . 'index.php' );

// include all databse classes
require_once( DBPATH . 'function.php' );

//check up to exsist of DB class
if ( !class_exists('DB') ) {
    error_code('can not find db class.', __FILE__, __LINE__);
}

//global db variable
$db = require_db();

// proceeding for request from mobile
require_once( DBPATH . 'controlDB.php' );
?>
