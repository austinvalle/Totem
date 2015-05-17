<?php
	// for autoloading classes. function will never actually be called
	function my_autoloader($class) {
		if(!class_exists($class)) {
			include_once "classes/{$class}.class.php";
		}
	}
	spl_autoload_register('my_autoloader');
?>