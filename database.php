<?php
	include_once('classLoader.php');

	$DB_PARAMS = array('server'=>'localhost','database'=>'totem','username'=>'root','password'=>'');
	$DATABASE = new DBConnect;
	$DATABASE->load($DB_PARAMS);
	if(!$DATABASE->connect()) {
		die('Can\'t establish a connection to the database: ' . mysql_error());
	}

?>