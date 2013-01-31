<?php

/********************************************************
 * A Search API script built on a Search object 
 * Creates set lists to group fields to types of queries
 *
 * @author ikcede
 ********************************************************/

include_once("Models/Search.class.php");


/**
 * $fields array
 * Add elements like this:
 * Either "element" => "="
 * or "element" => array("=","OR")
 */
$fields = array(
	

);

$search = new Search("test");
$search_type = "simple";

if(isset($_GET["search_type"])) {
	$search_type = $_GET["search_type"];
}

if($search_type == "simple") {
	// Simple search: will match all get values as search items
	// Everything just gets added in the order it is found on $_GET
	foreach ($_GET as $key=>$val) {
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