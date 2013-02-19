<?php

/********************************************************
 * A Search API script built on a Search object 
 * Creates an array to group fields to types of queries
 *
 * See the github at https://github.com/ikcede/php-search
 *
 * @author ikcede
 *
 * DOCUMENTATION:
 * Make all GET requests to .../search.php
 *
 * Look at the $fields table to see how each field will 
 * query the database.
 * Ex. .../search.php?name=test will query the database for
 * rows where `name` LIKE 'test'
 ********************************************************/

include_once("Models/Search.class.php");

/**
 * $fields array
 * Add elements like this:
 * Either "element" => "="
 * or "element" => array("=","OR")
 * --to each array indexed by table/search_type
 */
$all_fields = array(
	"simple" => array(
		"name" 	=> "LIKE",
		"id" 	=> "="
	)

);

$search_type = "simple";

if(isset($_GET["search_type"])) {
	$search_type = $_GET["search_type"];
}
$limit = isset($_GET["limit"]) && is_numeric($_GET["limit"]) ? $_GET["limit"] : 0;
$search = new Search("test", $limit);
$fields = isset($all_fields[$search_type]) ? $all_fields[$search_type] : array();

if($search_type == "simple") {

	// Simple search: will match all get values as search items
	// Everything just gets added in the order it is found on $_GET
	
	foreach ($_GET as $key=>$val) {
		$key = trim($key);
		if(array_key_exists($key,$fields)) {
			$con = "AND";
			$type = "=";
			if(is_string($fields[$key])) {
				$type = $fields[$key];
			}
			else {
				$con = $fields[$key][1];
				$type = $fields[$key][0];
			}
			$search->addToken($key,$val,$type,$con,1);
		}
	}
	
	die(json_encode($search->search()));
	
}

?>