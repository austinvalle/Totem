<?php

class Post {
	
	var $properties;
	var $comments;
	
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

	function addComments($items) {
		if ($items) {
			if (is_array($items)) {
				foreach($items as $item) {
					$comments[] = $item;
				}
			}
			else {
				$comments[] = $item;
			}
		}
	}
}

?>