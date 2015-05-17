<?php
	include('sessionStart.php');
	
	$title = $_POST['title'];
	$body = $_POST['body'];
	$insertQuery = "INSERT INTO POSTS SET user_id = {$_SESSION['user']['id']}, title = '{$title}', body = '{$body}', date_posted = '" . date('Y-m-d h:i:s') . "'";
	$DATABASE->executeQuery($insertQuery);
?>