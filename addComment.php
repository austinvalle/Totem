<?php
	include('sessionStart.php');
	
	$text = $_POST['text'];
	$id = $_POST['id'];

	$query = "INSERT INTO COMMENTS SET user_id = {$_SESSION['user']['id']}, post_id = {$id}, body = '" . mysql_real_escape_string($text) . "', date_posted = NOW()";
	$DATABASE->executeQuery($query);
?>