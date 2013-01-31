<?php 

/********************************************************************
 * A PHP Class for setting up a basic MYSQLi database variable $db
 *
 * @author ikcede
 ********************************************************************/

$conf = array();

$conf['user'] = '';         // USERNAME  
$conf['pass'] = '';  		// PASSWORD
$conf['host'] = '';  		// HOST (ex. localhost)
$conf['database'] = '';     // DATABASE (or you can prefix everything)

global $db;
$db = new mysqli($conf['host'], 
	$conf['user'], 
	$conf['pass']);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

// Set database
if(!$db->select_db($conf['database'])) {
	$SQL = "CREATE DATABASE IF NOT EXISTS " . $conf['database'];
	if(!$result = $db->query($SQL)){
    	die('There was an error creating the database.');
	}
}

unset($conf);

?>