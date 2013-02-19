<?php

/********************************************************
 * A Search Object class
 *
 * @author ikcede
 ********************************************************/

require_once("Models/dbconfig.mysql.php");

class Search {
	
	var $table;
	var $limit;
	var $expression = NULL;
	
	// Constructor
	public function __construct($table, $limit = 0, $expression = "") {
		$this->table = $table;
		$this->limit = $limit;
		$this->expression = $expression;
	}
	
	/**
	 * Adds a new token to the token list
	 *
	 * @param $field: column name
	 * @param $data: column data
	 * @param $type: =, <, >, LIKE, etc.
	 * @param $con: AND, OR, AND NOT, OR NOT
	 * @param $priority: 0 or positive
	 *
	 * @return: string representation of the new expression
	 */ 
	public function addToken($field, $data, $type="=", $con="AND", $priority=0) {
	
		// Wildcard support, using wildcard *
		if($type == "LIKE") {$data=str_replace("*","%",$data);}
	
		// Clean data for strings vs ints
		if($type == ">" || $type == "<" || $type == ">=" || $type == "<=") {
			$data = $this->__clean($data,true);
		} else $data = $this->__clean($data);
	
		// Build expression
		if($this->expression == "") {
			return $this->expression = "`$field` $type $data";
		}
		if($priority > 0) {
			return $this->expression = $this->wrapParens() . 
				" $con `$field` $type $data";
		}
		return $this->expression = $this->expression . " $con `$field` $type $data";
	}
	
	/**
	 * Extend the search with another search expression
	 *
	 * @param $search: the other search object to combine with
	 * @param $con: AND, OR, AND NOT, OR NOT
	 * 
	 * @return: string representation of the new expression
	 */
	public function extend(&$search, $con = "AND") {
		return $this->expression = $this->wrapParens() . " $con " . $search->wrapParens();
	}
	
	
	/**
	 * Primary search function
	 * Conducts a search of the database
	 *
	 * @param $col: Search only these columns
	 *
	 * @return: error array or the resulting rows in an assoc array
	 */
	public function search($col = "*") {
		global $db; 
		
		// Return empty if no search queries
		if($this->expression == "") return array();
		
		// Create the SQL query
		$sql = "SELECT $col FROM ".$this->table." WHERE ".$this->expression;
		
		// Set limit for search
		if($this->limit > 0) {
			$sql .= " LIMIT " . $this->limit;
		}
		
		$result = $db->query($sql);
		if(!$result) {
			return array('error'=>'search error');
		}
		else return $this->__fetch_all($result);
		
	}
	
	// Getter for expression
	public function getExpression() {
		if($expression != NULL) return $this->$expression;
		return "";
	}
	
	// Wraps the expression with one level of parentheses
	public function wrapParens() {
		$this->expression = "(".$this->expression .")";
		return $this->expression;
	}
	
	// Deprecation
	private function __fetch_all($result, $resulttype = MYSQLI_ASSOC) {
		
		for ($res = array(); $tmp = $result->fetch_array($resulttype);) $res[] = $tmp;
	
		return $res;
	}
	
	// Cleans up a data value to be used in a query
	private function __clean($data, $int = false) {
		global $db;
		if($int && is_numeric($data)) {
			return $db->escape_string($data);
		}
		return "'" . $db->escape_string($data) . "'";
	}
	
}

?>