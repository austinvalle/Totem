<?php
	include('sessionStart.php');
	
	$id = $_POST['id'];

	$query = "INSERT INTO LIKES SET user_id = {$_SESSION['user']['id']}, comment_id = {$id}";
	$DATABASE->executeQuery($query);
?>