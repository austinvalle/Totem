<?php

class Comment {
	
	var $properties;
	var $likes;
	
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
	
	function addLikes($items) {
		if ($items) {
			if (is_array($items)) {
				foreach($items as $item) {
					$likes[] = $item;
				}
			}
			else {
				$likes[] = $item;
			}
		}
	}
	
}

?>