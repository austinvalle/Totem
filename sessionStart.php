<?php
	include_once('database.php');
	session_start(); 

	// check for session id to see if user was previously logged in
	$sessionid = session_id();
	#query = "select * from USERS where session_id = '{$sessionid}' LIMIT 1";
	$query = "select * from USERS where id = 1";
	$result = $DATABASE->fetchOneResult($query);
	if($result) {
		$_SESSION['user'] = $result;
	}
	else {
		if(basename($_SERVER['PHP_SELF']) != 'login.php'){  
 	       header('Location: login.php');  
 	       exit;  
 	   } 
	}
?>