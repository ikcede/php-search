<?php

/********************************************************
 * A Search Object class
 *
 * @author ikcede
 ********************************************************/

require_once("dbconfig.mysql.php");

class Search {
	
	var $table;
	var $expression = NULL;
	
	// Constructor
	public function __construct($table, $expression = "") {
		$this->table = $table;
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
		if($this->expression == "") {
			return $this->expression = "`".$field . "` $type " . $this->__clean($data);
		}
		if($priority > 0) {
			return $this->expression = $this->wrapParens() . 
				" $con `$field` $type " . $this->__clean($data);
		}
		return $this->expression = $this->expression . " $con `$field` $type " . $this->__clean($data);
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
		
		$sql = "SELECT $col FROM ".$this->table." WHERE ".$this->expression;
		
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
	private function __clean($data) {
		global $db;
		return "'" . $db->escape_string($data) . "'";
	}
	
}

?>