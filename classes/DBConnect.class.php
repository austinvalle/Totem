<?php
// Database connection wrapper class

class DBConnect {
	var $parameters;
	var $linkId;
	
	function DBConnect() {
	
	}
	
	function load($params) {
		if ($params) {
			if (is_array($params)) {
				foreach($params as $key=>$val) {
					$this->parameters[$key] = $val;
				}
			}
			else {
				$this->parameters = $params;
			}
		}
	}
	
	function connect() {
		$link = mysql_connect($this->parameters['server'], $this->parameters['username'], $this->parameters['password']);
		if($link) {
			$db = mysql_select_db($this->parameters['database'],$link);
			if(!$db) {
				return false;
			}
			$this->linkId = $link;
		}
		else {
			return false;
		}
		return true;
	}
	
	function open() {
		$this->connect();
	}
	
	function close() {
		mysql_close($this->linkId);	
	}
	
	function getId() {
		return $this->linkId;
	}
	
	function set($key, $val) {
		if ($key && $val) {
			$this->parameters[$key] = $val;
		}
	}
	
	function get($key) {
		if ($key) {
			return $this->paramters[$key];
		}
	}
	
	function clearParameters($keys = null) {
		if ($keys) {
			if (is_array($keys)) {
				foreach($keys as $key) {
					unset($this->parameters[$key]);
				}
			}
			else {
				unset($this->parameters[$keys]);
			}
		}
		else {
			$this->parameters = array();
		}
	}
	
	function executeQuery($query) {
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) {
			return $result;
		}
		return null;
	}
	
	function resultlessQuery($query) {
		return mysql_query($query);
	}
	
	// returns an array of associative array of query results
	function executeAssocQuery($query) {
          $result = $this->executeQuery($query); 
          if($result) { 
             while( $row = mysql_fetch_assoc($result)){
                $results[] = $row;
             }
             return $results;
          } 
          return null; 
	}

	// returns an associative array of the first or only query
	function fetchOneResult($query) {
          $result = $this->executeQuery($query); 
          if($result) { 
             return mysql_fetch_assoc($result); 
          } 
             return null; 
	}

}

?>