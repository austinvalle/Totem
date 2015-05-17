<?php

class Like {

	var $properties;
	
	function load($items) {
		if ($items) {
			if (is_array($items)) {
				foreach($items as $key=>$val) {
					$this->properties[$key] = $val;
				}
			}
			else {
				$this->properties = $items;
			}
		}
	}
	
}

?>